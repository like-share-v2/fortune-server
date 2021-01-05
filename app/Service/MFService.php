<?php
declare (strict_types=1);
/**
 * @copyright uid
 * @version   1.0.0
 * @link      https://baidu.com
 */

namespace App\Service;

use App\Exception\LogicException;
use App\Kernel\Utils\JwtInstance;
use App\Kernel\Utils\Logger;
use App\Model\UserBill;
use App\Service\DAO\MFModeDAO;
use App\Service\DAO\MFOrderDAO;

use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Snowflake\IdGeneratorInterface;
use Hyperf\Utils\Context;
use Psr\Container\ContainerInterface;

/**
 * 理财服务
 *
 * @author  baidu.com
 * @package App\Service
 */
class MFService
{
    /**
     * @Inject()
     * @var ContainerInterface
     */
    public $container;

    /**
     * @param int $id
     * @param int $amount
     */
    public function buy(int $id, int $amount)
    {
        if (!$mode = $this->container->get(MFModeDAO::class)->first($id)) {
            throw new LogicException('logic.PRODUCT_NOT_FOUND', 400);
        }
        if ($mode->is_enable === false) {
            throw new LogicException('logic.PRODUCT_NOT_FOR_SALE', 400);
        }
        if ($amount < $mode->min_amount) {
            Context::set('_replace', [
                'amount' => $mode->min_amount
            ]);
            throw new LogicException('logic.MIN_TRANSFER_IN', 400);
        }

        $user = JwtInstance::instance()->build()->getUser();
        if ($user->balance < $amount) {
            throw new LogicException('logic.INSUFFICIENT_BALANCE', 10097);
        }

        Db::beginTransaction();
        try {
            $this->container->get(MFOrderDAO::class)->create([
                'order_no'            => (string)$this->container->get(IdGeneratorInterface::class)->generate(),
                'user_id'             => $user->id,
                'mode_title'          => $mode->title,
                'mode'                => $mode->getRaw('mode'),
                'income_mode'         => $mode->getRaw('income_mode'),
                'daily_interest_rate' => $mode->getRaw('daily_interest_rate'),
                'amount'              => $amount,
                'profit'              => 0,
                'unfreeze_time'       => $mode->getRaw('mode') === 1 ? time() + $mode->period * 86400 : 0,
                'is_settle'           => 0
            ]);

            UserBill::query()->create([
                'user_id'        => $user->id,
                'type'           => 15,
                'balance'        => -$amount,
                'before_balance' => $user->balance,
                'after_balance'  => $user->balance - $amount
            ]);
            $user->balance -= $amount;
            $user->save();

            Db::commit();
        }
        catch (\Throwable $e) {
            Db::rollBack();
            Logger::get('mf')->error($e->getMessage());
            throw new LogicException('logic.SERVER_ERROR', 400);
        }
    }

    /**
     * 卖出
     *
     * @param string $order_no
     */
    public function sell(string $order_no)
    {
        if (!$order = $this->container->get(MFOrderDAO::class)->findByOrderNo($order_no)) {
            throw new LogicException('logic.ORDER_NOT_FOUND');
        }
        $user = JwtInstance::instance()->build()->getUser();
        if ($order->user_id !== $user->id) {
            throw new LogicException('logic.ORDER_NOT_FOUND');
        }
        if ($order->getRaw('unfreeze_time') > 0 && time() < $order->getRaw('unfreeze_time')) {
            throw new LogicException('logic.NOT_ARRIVED_UNFREEZE_TIME');
        }
        if ($order->is_settle === 1) {
            return;
        }

        Db::beginTransaction();
        try {
            $order->is_settle   = 1;
            $order->settle_time = time();

            $total = ($order->amount + $order->profit);
            UserBill::query()->create([
                'user_id'        => $user->id,
                'type'           => 16,
                'balance'        => $total,
                'before_balance' => $user->balance,
                'after_balance'  => $user->balance + $total
            ]);

            $user->balance += $total;
            $user->save();
            $order->save();

            Db::commit();
        }
        catch (\Throwable $e) {
            Db::rollBack();
            Logger::get('mf')->error($e->getMessage());
            throw new LogicException('logic.SERVER_ERROR');
        }
    }
}