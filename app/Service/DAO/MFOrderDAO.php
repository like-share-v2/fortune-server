<?php
declare (strict_types=1);
/**
 * @copyright uid
 * @version   1.0.0
 * @link      https://baidu.com
 */

namespace App\Service\DAO;

use App\Model\MFOrder;

use Hyperf\Contract\LengthAwarePaginatorInterface;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Collection;

/**
 * MFOrderDAO
 *
 * @author  baidu.com
 * @package App\Service\DAO
 */
class MFOrderDAO
{
    /**
     * @param array $data
     *
     * @return MFOrder
     */
    public function create(array $data): ?MFOrder
    {
        return MFOrder::create($data);
    }

    /**
     * @param string $order_no
     *
     * @return mixed
     */
    public function findByOrderNo(string $order_no): ?MFOrder
    {
        return MFOrder::where('order_no', $order_no)->first();
    }

    /**
     * 获取未结算的订单列表
     *
     * @return Builder[]|Collection
     */
    public function getNotSettleOrder()
    {
        return MFOrder::where('is_settle', 0)->with('user')->get();
    }

    /**
     * 获取用户订单列表
     *
     * @param int $user_id
     *
     * @return LengthAwarePaginatorInterface
     */
    public function getOrderListByUserId(int $user_id)
    {
        return MFOrder::where('user_id', $user_id)->orderBy('is_settle')->orderByDesc('created_at')->orderBy('id')->paginate(15);
    }

    /**
     * 获取账户余额
     *
     * @param int $user_id
     *
     * @return int
     */
    public function getTotalAmount(int $user_id)
    {
        return (float)MFOrder::where('user_id', $user_id)->where('is_settle', 0)->sum('amount');
    }
}