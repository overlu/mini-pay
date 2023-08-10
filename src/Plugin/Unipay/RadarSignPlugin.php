<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Unipay;

use Closure;
use GuzzleHttp\Psr7\Utils;
use Mini\Support\Collection;
use MiniPay\Contract\PluginInterface;
use MiniPay\Exception\InvalidConfigException;
use MiniPay\Logger;
use MiniPay\Rocket;
use MiniPay\Traits\GetUnipayCerts;

use function MiniPay\get_unipay_config;

/**
 * Class RadarSignPlugin
 * @package MiniPay\Plugin\Unipay
 */
class RadarSignPlugin implements PluginInterface
{
    use GetUnipayCerts;

    /**
     * @param Rocket $rocket
     * @param Closure $next
     * @return Rocket
     * @throws InvalidConfigException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[unipay][PreparePlugin] 插件开始装载', ['rocket' => $rocket]);

        $this->sign($rocket);

        $this->reRadar($rocket);

        Logger::info('[unipay][PreparePlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    /**
     * @param Rocket $rocket
     * @throws InvalidConfigException
     */
    protected function sign(Rocket $rocket): void
    {
        $payload = $rocket->getPayload()->filter(fn($v, $k) => 'signature' !== $k);
        $config = $this->getConfig($rocket->getParams());

        $rocket->mergePayload([
            'signature' => $this->getSignature($config['certs']['pkey'] ?? '', $payload),
        ]);
    }

    protected function reRadar(Rocket $rocket): void
    {
        $body = $this->getBody($rocket->getPayload());
        $radar = $rocket->getRadar();

        if (!empty($body) && $radar !== null) {
            $radar = $radar->withBody(Utils::streamFor($body));

            $rocket->setRadar($radar);
        }
    }

    /**
     * @param array $params
     * @return array
     * @throws InvalidConfigException
     */
    protected function getConfig(array $params): array
    {
        $config = get_unipay_config($params);

        if (empty($config['certs']['pkey'])) {
            $this->getCertId($params['_config'] ?? 'default', $config);

            $config = get_unipay_config($params);
        }

        return $config;
    }

    protected function getSignature(string $pkey, Collection $payload): string
    {
        $content = $payload->sortKeys()->toString();

        openssl_sign(hash('sha256', $content), $sign, $pkey, 'sha256');

        return base64_encode($sign);
    }

    protected function getBody(Collection $payload): string
    {
        return $payload->query();
    }
}
