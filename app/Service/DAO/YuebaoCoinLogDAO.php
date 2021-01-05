<?php
declare (strict_types=1);
/**
 * @copyright uid
 * @version   1.0.0
 * @link      https://baidu.com
 */

namespace App\Service\DAO;

use App\Model\YuebaoCoinLog;

use Hyperf\Contract\LengthAwarePaginatorInterface;

/**
 * YuebaoCoinLog
 *
 * @author  baidu.com
 * @package App\Service\DAO
 */
class YuebaoCoinLogDAO
{
    /**
     * @param array $data
     *
     * @return YuebaoCoinLog
     */
    public function create(array $data): YuebaoCoinLog
    {
        return YuebaoCoinLog::create($data);
    }

    /**
     * 获取资金日志记录
     *
     * @param int   $user_id
     * @param array $params
     *
     * @return LengthAwarePaginatorInterface
     */
    public function getCoinLog(int $user_id, array $params)
    {
        $model = YuebaoCoinLog::where('user_id', $user_id);

        if (isset($params['type'])) {
            $model->where('type', (int)$params['type']);
        }

        return $model->orderByDesc('record_time')->orderBy('id')->paginate(15);
    }
}