<?php
declare (strict_types=1);
/**
 * @copyright uid
 * @version   1.0.0
 * @link      https://baidu.com
 */

namespace App\Crontab;

use App\Kernel\Utils\Logger;
use App\Model\MFOrder;
use App\Service\DAO\MFIncomeDAO;
use App\Service\DAO\MFOrderDAO;

use Hyperf\Crontab\Annotation\Crontab;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;
use Psr\Container\ContainerInterface;

/**
 * 理财发放收益
 *
 * @Crontab(name="MFIncomeCrontab", rule="00 01 * * *", callback="execute", memo="每天凌晨发放理财收益")
 * @author  baidu.com
 * @package App\Crontab
 */
class MFIncomeCrontab
{
    /**
     * @Inject()
     * @var ContainerInterface
     */
    public $container;

    public function execute()
    {
        $today = date('Ymd');
        foreach ($this->container->get(MFOrderDAO::class)->getNotSettleOrder() as $order) {
            /** @var MFOrder $order */
            $cacheKey = sprintf('MFSettleIssued:%s:%d', $today, $order->id);
            if ($this->container->get(Redis::class)->exists($cacheKey)) {
                continue;
            }
            // 购买未满24小时不获得收益
            if ((time() - $order->getRaw('created_at')) < 60 * 60 * 24) {
                continue;
            }
            go(function () use ($order, $cacheKey) {
                Db::beginTransaction();
                try {
                    $income = 0;
                    // 本金
                    if ($order->getRaw('income_mode') === 1) {
                        $income = $order->amount * $order->getRaw('daily_interest_rate');
                    }
                    // 本利
                    if ($order->getRaw('income_mode') === 2) {
                        $income = ($order->amount + $order->profit) * $order->getRaw('daily_interest_rate');
                    }
                    $order->profit += $income;
                    $order->save();

                    $this->container->get(MFIncomeDAO::class)->create([
                        'user_id'     => $order->user->id,
                        'mf_order_id' => $order->id,
                        'amount'      => $income,
                        'record_time' => time()
                    ]);

                    $this->container->get(Redis::class)->set($cacheKey, 'ok', 86400);

                    Db::commit();
                }
                catch (\Throwable $e) {
                    Db::rollBack();
                    Logger::get('mf')->error($e->getMessage());
                }
            });
        }
    }
}