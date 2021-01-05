<?php
declare (strict_types=1);
/**
 * @copyright uid
 * @version   1.0.0
 * @link      https://baidu.com
 */

namespace App\Service\DAO;

use App\Model\YuebaoAccount;

use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Collection;

/**
 * YuebaoAccountDAO
 *
 * @author  baidu.com
 * @package App\Service\DAO
 */
class YuebaoAccountDAO
{
    /**
     * @param array $data
     *
     * @return mixed
     */
    public function create(array $data): ?YuebaoAccount
    {
        return YuebaoAccount::query()->create($data);
    }

    /**
     * 通过用户ID获取余额宝账户
     *
     * @param int $user_id
     *
     * @return mixed
     */
    public function firstByUserId(int $user_id): ?YuebaoAccount
    {
        return YuebaoAccount::query()->where('user_id', $user_id)->first();
    }

    /**
     * 获取余额宝账户列表
     *
     * @return Builder[]|Collection
     */
    public function getAccountList()
    {
        return YuebaoAccount::query()->get();
    }
}