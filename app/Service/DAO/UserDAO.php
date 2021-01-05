<?php
declare (strict_types=1);
/**
 * @copyright uid
 * @version   1.0.0
 * @link      https://baidu.com
 */

namespace App\Service\DAO;

use App\Exception\LogicException;
use App\Model\User;

/**
 * UserDAO
 *
 * @author  baidu.com
 * @package App\Service\DAO
 */
class UserDAO
{
    /**
     * @param int  $id
     * @param bool $throw
     *
     * @return mixed
     */
    public function find(int $id, bool $throw = true): ?User
    {
        $data = User::find($id);
        if (!$data && $throw) {
            throw new LogicException('user does not exist');
        }
        return $data;
    }

    /**
     * @param int   $user_id
     * @param array $data
     *
     * @return int
     */
    public function update(int $user_id, array $data)
    {
        return User::where('id', $user_id)->update($data);
    }
}