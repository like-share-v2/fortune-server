<?php

declare(strict_types=1);
/**
 * @copyright uid
 * @version   1.0.0
 * @link      https://baidu.com
 */

namespace App\Exception\Handler;

use App\Exception\ResponseException;

use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Codec\Json;
use Hyperf\Utils\Context;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * 逻辑异常接管
 *
 * @author  baidu.com
 * @package App\Model
 */
class ResponseExceptionHandler extends ExceptionHandler
{
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $data = [
            'code'    => $throwable->getCode(),
            'message' => __($throwable->getMessage(), Context::get('_replace', [])),
            'result'  => Context::get('successful_data') // 从协程上下文获取返回数据
        ];

        // 如果设置了错误回显
        if (Context::has('errors')) {
            $data['errors'] = array_map(function ($error) {
                return __($error);
            }, Context::get('errors'));
            unset($data['result']);
        }

        // 阻止事件冒泡
        $this->stopPropagation();

        // 释放协程上下文
        Context::destroy('successful_data');
        Context::destroy('errors');

        $response = $response->withStatus(200)
            ->withBody(new SwooleStream(Json::encode($data, JSON_UNESCAPED_UNICODE)))
            ->withHeader('Content-Type', 'application/json;charset=utf-8');

        // 交换token
        if (Context::has('ExchangeToken')) {
            $response = $response->withHeader('Exchange-Token', Context::get('ExchangeToken'));
        }

        return $response;
    }

    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof ResponseException;
    }
}
