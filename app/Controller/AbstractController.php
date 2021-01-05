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

namespace App\Controller;

use App\Exception\ResponseException;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Utils\Context;
use Psr\Container\ContainerInterface;

abstract class AbstractController
{
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject
     * @var RequestInterface
     */
    protected $request;

    /**
     * @Inject
     * @var ResponseInterface
     */
    protected $response;

    /**
     * 错误响应
     *
     * @param string $message
     * @param int    $code
     * @param array  $replace
     */
    protected function error(string $message, int $code = 400, array $replace = [])
    {
        Context::set('_replace', $replace);

        throw new ResponseException($message, $code);
    }

    /**
     * 针对表单的错误响应
     *
     * @param array $errors
     *
     * @throws ResponseException
     */
    protected function formError(array $errors = [])
    {
        Context::set('errors', $errors);

        throw new ResponseException('', 400);
    }

    /**
     * 成功响应
     *
     * @param mixed $data
     *
     * @throws ResponseException
     */
    protected function success($data = [])
    {
        Context::set('successful_data', $data);

        throw new ResponseException('success', 200);
    }
}
