<?php
/**
 * @copyright uid
 * @version   1.0.0
 * @link      https://baidu.com
 */

use Hyperf\Utils\ApplicationContext;
use Psr\SimpleCache\CacheInterface;

if (!function_exists('getConfig')) {
    /**
     * 获取配置
     *
     * @param string $key
     * @param        $default
     *
     * @return mixed
     */
    function getConfig(string $key, $default = null)
    {
        try {
            $configs = ApplicationContext::getContainer()->get(CacheInterface::class)->get('AppConfigs');
            return $configs[$key] ?? config($key, $default);
        }
        catch (\Psr\SimpleCache\InvalidArgumentException $e) {
            return $default;
        }
    }
}