<?php
declare (strict_types=1);
/**
 * @copyright uid
 * @version   1.0.0
 * @link      https://baidu.com
 */

namespace App\Service\DAO;

use App\Model\MFIncome;

use Carbon\Carbon;

/**
 * MFIncomeDAO
 *
 * @author  baidu.com
 * @package App\Service\DAO
 */
class MFIncomeDAO
{
    /**
     * 创建
     *
     * @param array $data
     *
     * @return MFIncome
     */
    public function create(array $data): ?MFIncome
    {
        return MFIncome::create($data);
    }

    /**
     * 获取总收益
     *
     * @param int $user_id
     *
     * @return int
     */
    public function getTotalIncome(int $user_id)
    {
        return (float)MFIncome::query()->where('user_id', $user_id)->sum('amount');
    }

    /**
     * 获取昨日收益
     *
     * @param int $user_id
     *
     * @return int
     */
    public function getYesterdayIncome(int $user_id)
    {
        $yesterday = Carbon::yesterday()->getTimestamp();
        return (float)MFIncome::query()
            ->where('user_id', $user_id)
            ->whereBetween('record_time', [
                $yesterday,
                $yesterday + 86399
            ])
            ->sum('amount');
    }
}