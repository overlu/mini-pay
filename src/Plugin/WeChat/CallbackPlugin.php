<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat;

use Closure;
use Mini\Support\Collection;
use Psr\Http\Message\ServerRequestInterface;
use MiniPay\Contract\PluginInterface;
use MiniPay\Direction\NoHttpRequestDirection;
use MiniPay\Exception\Exception;
use MiniPay\Exception\InvalidConfigException;
use MiniPay\Exception\InvalidParamsException;
use MiniPay\Exception\InvalidResponseException;
use MiniPay\Logger;
use MiniPay\Rocket;

use function MiniPay\decrypt_wechat_resource;
use function MiniPay\verify_wechat_sign;

/**
 * Class CallbackPlugin
 * @package MiniPay\Plugin\WeChat
 */
class CallbackPlugin implements PluginInterface
{
    /**
     * @param Rocket $rocket
     * @param Closure $next
     * @return Rocket
     * @throws InvalidConfigException
     * @throws InvalidParamsException
     * @throws InvalidResponseException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[wechat][CallbackPlugin] 插件开始装载', ['rocket' => $rocket]);

        $this->formatRequestAndParams($rocket);

        /* @phpstan-ignore-next-line */
        verify_wechat_sign($rocket->getDestinationOrigin(), $rocket->getParams());

        $body = json_decode((string)$rocket->getDestination()->getBody(), true);

        $rocket->setDirection(NoHttpRequestDirection::class)->setPayload(new Collection($body));

        $body['resource'] = decrypt_wechat_resource($body['resource'] ?? [], $rocket->getParams());

        $rocket->setDestination(new Collection($body));

        Logger::info('[wechat][CallbackPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    /**
     * @throws InvalidParamsException
     */
    protected function formatRequestAndParams(Rocket $rocket): void
    {
        $request = $rocket->getParams()['request'] ?? null;

        if (!$request instanceof ServerRequestInterface) {
            throw new InvalidParamsException(Exception::REQUEST_NULL_ERROR);
        }

        $rocket->setDestination(clone $request)
            ->setDestinationOrigin($request)
            ->setParams($rocket->getParams()['params'] ?? []);
    }
}
