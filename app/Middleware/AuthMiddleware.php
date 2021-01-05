<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Constants\Constants;
use App\Exception\ResponseException;
use App\Kernel\Utils\JwtInstance;

use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class AuthMiddleware implements MiddlewareInterface
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        // 获取Token
        $token = $request->getHeaderLine(Constants::AUTHORIZATION);
        if (empty($token)) {
            throw new ResponseException('logic.NEED_LOGIN', 401);
        }

        $user = JwtInstance::instance()->decode($token)->getUser();
        // 判断用户状态
        if (!$user || $user->status !== 1) {
            throw new ResponseException('logic.USER_STATUS_UNUSUAL', 401);
        }

        return $handler->handle($request);
    }
}