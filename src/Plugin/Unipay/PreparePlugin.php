<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Unipay;

use Closure;
use Mini\Support\Str;
use MiniPay\Contract\PluginInterface;
use MiniPay\Exception\InvalidConfigException;
use MiniPay\Logger;
use MiniPay\Rocket;
use MiniPay\Traits\GetUnipayCerts;

use function MiniPay\get_tenant;
use function MiniPay\get_unipay_config;

/**
 * Class PreparePlugin
 * @package MiniPay\Plugin\Unipay
 */
class PreparePlugin implements PluginInterface
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

        $rocket->mergePayload($this->getPayload($rocket->getParams()));

        Logger::info('[unipay][PreparePlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    /**
     * @param array $params
     * @return array
     * @throws InvalidConfigException
     */
    protected function getPayload(array $params): array
    {
        $tenant = get_tenant($params);
        $config = get_unipay_config($params);

        $init = [
            'version' => '5.1.0',
            'encoding' => 'utf-8',
            'backUrl' => $config['notify_url'] ?? '',
            'currencyCode' => '156',
            'accessType' => '0',
            'signature' => '',
            'signMethod' => '01',
            'merId' => $config['mch_id'] ?? '',
            'frontUrl' => $config['return_url'] ?? '',
            'certId' => $this->getCertId($tenant, $config),
        ];

        return array_merge(
            $init,
            array_filter($params, static fn($v, $k) => !Str::startsWith((string)$k, '_'), ARRAY_FILTER_USE_BOTH),
        );
    }
}
