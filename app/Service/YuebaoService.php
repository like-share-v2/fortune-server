<?php
declare (strict_types=1);
/**
 * @copyright uid
 * @version   1.0.0
 * @link      https://baidu.com
 */

namespace App\Service;

use App\Exception\LogicException;
use App\Kernel\Utils\Logger;
use App\Model\UserBill;
use App\Model\YuebaoAccount;
use App\Service\DAO\UserDAO;
use App\Service\DAO\YuebaoAccountDAO;
use App\Service\DAO\YuebaoCoinLogDAO;

use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Context;
use Psr\Container\ContainerInterface;

/**
 * 余额宝服务
 *
 * @author  baidu.com
 * @package ${NAMESPACE}
 */
class YuebaoService
{
    /**
     * @Inject()
     * @var ContainerInterface
     */
    public $container;

    /**
     * 获取余额宝账户
     *
     * @param int $user_id
     *
     * @return mixed
     */
    public function getYuebaoAccount(int $user_id): YuebaoAccount
    {
        if (!$account = $this->container->get(YuebaoAccountDAO::class)->firstByUserId($user_id)) {
            // 用户未创建余额宝账户，自动创建账户
            $account = $this->container->get(YuebaoAccountDAO::class)->create([
                'user_id'       => $user_id,
                'balance'       => 0,
                'withdraw_time' => 0
            ]);
        }

        return $account;
    }

    /**
     * 转入余额到余额宝
     *
     * @param int   $user_id
     * @param float $amount
     *
     * @throws LogicException
     */
    public function transferToYuebao(int $user_id, float $amount)
    {
        $yuebaoAccount = $this->getYuebaoAccount($user_id);
        if (!$user = $this->container->get(UserDAO::class)->find($user_id)) {
            throw new LogicException('logic.USER_NOT_FOUND');
        }
        // 判断用户余额是否充足
        if ($user->balance < $amount) {
            throw new LogicException('logic.INSUFFICIENT_BALANCE');
        }
        Db::beginTransaction();
        try {
            $this->container->get(YuebaoCoinLogDAO::class)->create([
                'user_id' => $user->id,
                'type'    => 1,
                'amount'  => $amount,
                'balance' => $yuebaoAccount->balance + $amount,
                'remark'  => ''
            ]);

            UserBill::query()->create([
                'user_id'        => $user->id,
                'type'           => 13,
                'balance'        => -$amount,
                'before_balance' => $user->balance,
                'after_balance'  => $user->balance - $amount
            ]);

            $yuebaoAccount->balance       += $amount;
            $yuebaoAccount->withdraw_time = time() + (getConfig('yuebao_freezing_day', 3) * 86400);
            $yuebaoAccount->save();

            $user->balance -= $amount;
            $user->save();

            Db::commit();
        }
        catch (\Throwable $e) {
            Db::rollBack();
            Logger::get('yuebao')->error($e->getMessage());
            throw new LogicException('logic.SERVER_ERROR');
        }
    }

    /**
     * 余额宝转出到余额
     *
     * @param int   $user_id
     * @param float $amount
     *
     * @throws LogicException
     */
    public function transferOutToAccount(int $user_id, float $amount)
    {
        $yuebaoAccount = $this->getYuebaoAccount($user_id);
        if (!$user = $this->container->get(UserDAO::class)->find($user_id)) {
            throw new LogicException('logic.USER_NOT_FOUND');
        }
        // 判断是否到可提现时间
        if ($yuebaoAccount->withdraw_time > 0 && $yuebaoAccount->withdraw_time > time()) {
            Context::set('__replace', [
                'time' => date('m-d H:i', $yuebaoAccount->withdraw_time)
            ]);
            throw new LogicException('logic.CAN_TRANSFER_OUT_TIME');
        }
        // 判断用户余额是否充足
        if ($yuebaoAccount->balance < $amount) {
            throw new LogicException('logic.INSUFFICIENT_BALANCE');
        }
        Db::beginTransaction();
        try {
            $this->container->get(YuebaoCoinLogDAO::class)->create([
                'user_id' => $user->id,
                'type'    => 2,
                'amount'  => -$amount,
                'balance' => $yuebaoAccount->balance - $amount,
                'remark'  => ''
            ]);

            UserBill::query()->create([
                'user_id'        => $user->id,
                'type'           => 14,
                'balance'        => $amount,
                'before_balance' => $user->balance,
                'after_balance'  => $user->balance + $amount
            ]);

            $yuebaoAccount->balance -= $amount;
            $yuebaoAccount->save();

            $user->balance += $amount;
            $user->save();

            Db::commit();
        }
        catch (\Throwable $e) {
            Db::rollBack();
            Logger::get('yuebao')->error($e->getMessage());
            throw new LogicException('logic.SERVER_ERROR');
        }
    }
}