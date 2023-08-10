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
use MiniPay\Exception\Exception;
use MiniPay\Exception\InvalidConfigException;
use MiniPay\Logger;
use MiniPay\Pay;
use MiniPay\Provider\Alipay;
use MiniPay\Request;
use MiniPay\Rocket;

use function MiniPay\get_alipay_config;
use function MiniPay\get_private_cert;

/**
 * Class RadarSignPlugin
 * @package MiniPay\Plugin\Alipay
 */
class RadarSignPlugin implements PluginInterface
{
    /**
     * @param Rocket $rocket
     * @param Closure $next
     * @return Rocket
     * @throws InvalidConfigException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[alipay][RadarSignPlugin] 插件开始装载', ['rocket' => $rocket]);

        $this->sign($rocket);

        $this->reRadar($rocket);

        Logger::info('[alipay][RadarSignPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    /**
     * @param Rocket $rocket
     * @throws InvalidConfigException
     */
    protected function sign(Rocket $rocket): void
    {
        $this->formatPayload($rocket);

        $sign = $this->getSign($rocket);

        $rocket->mergePayload(['sign' => $sign]);
    }

    /**
     * @param Rocket $rocket
     */
    protected function reRadar(Rocket $rocket): void
    {
        $params = $rocket->getParams();

        $rocket->setRadar(new Request(
            $this->getMethod($params),
            $this->getUrl($params),
            $this->getHeaders(),
            $this->getBody($rocket->getPayload()),
        ));
    }

    protected function formatPayload(Rocket $rocket): void
    {
        $payload = $rocket->getPayload()->filter(fn($v, $k) => '' !== $v && !is_null($v) && 'sign' !== $k);

        $contents = array_filter($payload->get('biz_content', []), static fn($v, $k) => !Str::startsWith((string)$k, '_'), ARRAY_FILTER_USE_BOTH);

        $rocket->setPayload(
            $payload->merge(['biz_content' => json_encode($contents)])
        );
    }

    /**
     * @param Rocket $rocket
     * @return string
     * @throws InvalidConfigException
     */
    protected function getSign(Rocket $rocket): string
    {
        $privateKey = $this->getPrivateKey($rocket->getParams());

        $content = $rocket->getPayload()->sortKeys()->toString();

        openssl_sign($content, $sign, $privateKey, OPENSSL_ALGO_SHA256);

        return base64_encode($sign);
    }

    /**
     * @param array $params
     * @return string
     * @throws InvalidConfigException
     */
    protected function getPrivateKey(array $params): string
    {
        $privateKey = get_alipay_config($params)['app_secret_cert'] ?? null;

        if (is_null($privateKey)) {
            throw new InvalidConfigException(Exception::ALIPAY_CONFIG_ERROR, 'Missing Alipay Config -- [app_secret_cert]');
        }

        return get_private_cert($privateKey);
    }

    protected function getMethod(array $params): string
    {
        return strtoupper($params['_method'] ?? 'POST');
    }

    /**
     * @param array $params
     * @return string
     */
    protected function getUrl(array $params): string
    {
        $config = get_alipay_config($params);

        return Alipay::URL[$config['mode'] ?? Pay::MODE_NORMAL];
    }

    protected function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
    }

    protected function getBody(Collection $payload): string
    {
        return $payload->query();
    }
}
