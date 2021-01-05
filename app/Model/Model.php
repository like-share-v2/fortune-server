<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Model;

use Hyperf\DbConnection\Model\Model as BaseModel;

abstract class Model extends BaseModel
{
    /**
     * 获取原始值
     *
     * @param null $key
     * @param null $default
     *
     * @return mixed|null
     */
    public function getRaw($key = null, $default = null)
    {
        return $this->getAttributes()[$key] ?? $default;
    }
}
