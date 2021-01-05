<?php
declare (strict_types=1);
/**
 * @copyright uid
 * @version   1.0.0
 * @link      https://baidu.com
 */

namespace App\Service\DAO;

use App\Model\MFMode;

/**
 * MFModeDAO
 *
 * @author  baidu.com
 * @package App\Service\DAO
 */
class MFModeDAO
{
    /**
     * @param int $id
     *
     * @return MFMode
     */
    public function first(int $id): ?MFMode
    {
        return MFMode::find($id);
    }

    /**
     * 获取理财产品列表
     *
     * @return mixed
     */
    public function get()
    {
        return MFMode::where('is_enable', 1)->paginate(15);
    }
}