<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Alipay;

use Closure;
use Mini\Support\Collection;
use Mini\Support\Str;
use MiniPay\Contract\PluginInterface;
use MiniPay\Direction\NoHttpRequestDirection;
use MiniPay\Exception\Exception;
use MiniPay\Exception\InvalidConfigException;
use MiniPay\Exception\InvalidResponseException;
use MiniPay\Logger;
use MiniPay\Rocket;

use function MiniPay\verify_alipay_sign;

/**
 * Class CallbackPlugin
 * @package MiniPay\Plugin\Alipay
 */
class CallbackPlugin implements PluginInterface
{
    /**
     * @param Rocket $rocket
     * @param Closure $next
     * @return Rocket
     * @throws InvalidConfigException
     * @throws InvalidResponseException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[alipay][CallbackPlugin] 插件开始装载', ['rocket' => $rocket]);

        $this->formatPayload($rocket);
        $sign = $rocket->getParams()['sign'] ?? false;

        if (!$sign) {
            throw new InvalidResponseException(Exception::INVALID_RESPONSE_SIGN, '', $rocket->getParams());
        }

        verify_alipay_sign($rocket->getParams(), $this->getSignContent($rocket->getPayload()), $sign);

        $rocket->setDirection(NoHttpRequestDirection::class)
            ->setDestination($rocket->getPayload());

        Logger::info('[alipay][CallbackPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    protected function formatPayload(Rocket $rocket): void
    {
        $payload = (new Collection($rocket->getParams()))
            ->filter(fn($v, $k) => '' !== $v && !is_null($v) && 'sign' !== $k && 'sign_type' !== $k && !Str::startsWith($k, '_'));

        $rocket->setPayload($payload);
    }

    protected function getSignContent(Collection $payload): string
    {
        return $payload->sortKeys()->toString();
    }
}
