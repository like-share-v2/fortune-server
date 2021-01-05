<?php

declare(strict_types=1);
/**
 * @copyright uid
 * @version   1.0.0
 * @link      https://baidu.com
 */

namespace App\Middleware;

use Hyperf\Contract\TranslatorInterface;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * 前端国际化中间件
 *
 * @author  baidu.com
 * @package App\Model
 */
class TranslationMiddleware implements MiddlewareInterface
{
    /**
     * @Inject
     * @var TranslatorInterface
     */
    private $translator;

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $locale      = $request->getHeaderLine('Locale');
        $locale_list = [
            'zh-CN',
            'en-US',
            'ru-RU',
            'en-PH',
            'ko-KR',
            'ms-MY',
            'pt-PT',
            'tr-TR',
            'es-ES',
            'gu-IN',
            'id-ID',
            'vi-VN',
            'th-TH',
            'ar-SA'
        ];
        $locale      = $locale_list[$locale] ?? 'en-US';
        // 根据前端接口动态设置语言
        $this->translator->setLocale($locale);

        return $handler->handle($request);
    }
}
