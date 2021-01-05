<?php
declare (strict_types=1);
/**
 * @copyright uid
 * @version   1.0.0
 * @link      https://baidu.com
 */

namespace App\Crontab;

use App\Kernel\Utils\Logger;
use App\Model\YuebaoAccount;
use App\Service\DAO\YuebaoAccountDAO;
use App\Service\DAO\YuebaoCoinLogDAO;

use Hyperf\Crontab\Annotation\Crontab;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Redis\Redis;
use Psr\Container\ContainerInterface;

/**
 * 发放收益
 *
 * @Crontab(name="YuebaoIncomeCrontab", rule="01 00 * * *", callback="execute", memo="每天凌晨发放余额宝收益")
 * @author  baidu.com
 * @package App\Crontab
 */
class YuebaoIncomeCrontab
{
    /**
     * @Inject()
     * @var ContainerInterface
     */
    public $container;

    public function execute()
    {
        $interest_rate      = getConfig('daily_interest_rate', 0);
        $income_min_balance = getConfig('yuebao_income_min_balance', 0);
        foreach ($this->container->get(YuebaoAccountDAO::class)->getAccountList() as $account) {
            /** @var YuebaoAccount $account */
            if ($income_min_balance > 0 && $account->balance < $income_min_balance) {
                continue;
            }
            $cacheKey = sprintf('YuebaoIncomeIssued%s', $account->user_id);
            if ($this->container->get(Redis::class)->exists($cacheKey)) {
                continue;
            }
            $income = $account->balance * $interest_rate;
            if ($income < 0.01) {
                continue;
            }
            go(function () use ($account, $interest_rate, $income, $cacheKey) {
                Db::beginTransaction();
                try {
                    $account->balance += $income;
                    $account->save();
                    $this->container->get(YuebaoCoinLogDAO::class)->create([
                        'user_id'     => $account->user_id,
                        'type'        => 3,
                        'amount'      => $income,
                        'balance'     => $account->balance,
                        'record_time' => strtotime(date('Y-m-d')),
                        'remark'      => ''
                    ]);
                    $this->container->get(Redis::class)->set($cacheKey, 'ok', 86399);
                    Db::commit();
                }
                catch (\Throwable $e) {
                    Logger::get('yuebao')->error($e->getMessage());
                    Db::rollBack();
                }
            });
        }
    }
}