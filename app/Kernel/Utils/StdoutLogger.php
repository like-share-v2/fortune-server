<?php
declare (strict_types=1);
/**
 * @copyright uid
 * @version   1.0.0
 * @link      https://baidu.com
 */

namespace App\Kernel\Utils;

use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Utils\ApplicationContext;
use Psr\Log\LoggerInterface;

/**
 * StdoutLogger
 *
 * @author  baidu.com
 * @package App\Kernel\Utils
 */
class StdoutLogger
{
    /**
     * @return LoggerInterface
     */
    public static function get(): LoggerInterface
    {
        return ApplicationContext::getContainer()->get(StdoutLoggerInterface::class);
    }
}