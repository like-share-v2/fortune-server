<?php

declare(strict_types=1);
/**
 * @copyright uid
 * @version 1.0.0
 * @link https://baidu.com
 */

namespace App\Constants;

/**
 * 常量合集
 *
 * @author baidu.com
 * @package App\Constants
 */
class Constants
{
    /**
     * Authorization
     *
     * @var string
     */
    const AUTHORIZATION = 'Authorization';

    /**
     * Token 有效期
     *
     * @var int
     */
    const AUTHORIZATION_EXPIRE = 86400;

    /**
     * Token 提前续期时间
     *
     * @var integer
     */
    const AUTHORIZATION_RENEW = 2 * 60 * 60;
}
