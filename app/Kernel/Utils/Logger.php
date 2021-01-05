<?php
declare (strict_types=1);
/**
 * @copyright uid
 * @version   1.0.0
 * @link      https://baidu.com
 */

namespace App\Kernel\Utils;

use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\ApplicationContext;
use Psr\Log\LoggerInterface;

/**
 * Logger
 *
 * @author  baidu.com
 * @package App\Kernel\Utils
 */
class Logger
{
    /**
     * @param string $name
     * @param string $group
     *
     * @return LoggerInterface
     */
    public static function get(string $name, string $group = 'default'): LoggerInterface
    {
        return ApplicationContext::getContainer()->get(LoggerFactory::class)->get($name, $group);
    }
}