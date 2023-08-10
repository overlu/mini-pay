<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Alipay;

use Closure;
use MiniPay\Contract\PluginInterface;
use MiniPay\Exception\Exception;
use MiniPay\Exception\InvalidConfigException;
use MiniPay\Logger;
use MiniPay\Rocket;

use function MiniPay\get_alipay_config;
use function MiniPay\get_tenant;

/**
 * Class PreparePlugin
 * @package MiniPay\Plugin\Alipay
 */
class PreparePlugin implements PluginInterface
{
    /**
     * @param Rocket $rocket
     * @param Closure $next
     * @return Rocket
     * @throws InvalidConfigException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[alipay][PreparePlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->mergePayload($this->getPayload($rocket->getParams()));

        Logger::info('[alipay][PreparePlugin] 插件装载完毕', ['rocket' => $rocket]);

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
        $config = get_alipay_config($params);

        return [
            'app_id' => $config['app_id'] ?? '',
            'method' => '',
            'format' => 'JSON',
            'return_url' => $this->getReturnUrl($params, $config),
            'charset' => 'utf-8',
            'sign_type' => 'RSA2',
            'sign' => '',
            'timestamp' => date('Y-m-d H:i:s'),
            'version' => '1.0',
            'notify_url' => $this->getNotifyUrl($params, $config),
            'app_auth_token' => $this->getAppAuthToken($params, $config),
            'app_cert_sn' => $this->getAppCertSn($tenant, $config),
            'alipay_root_cert_sn' => $this->getAlipayRootCertSn($tenant, $config),
            'biz_content' => [],
        ];
    }

    protected function getReturnUrl(array $params, array $config): string
    {
        if (!empty($params['_return_url'])) {
            return $params['_return_url'];
        }

        return $config['return_url'] ?? '';
    }

    protected function getNotifyUrl(array $params, array $config): string
    {
        if (!empty($params['_notify_url'])) {
            return $params['_notify_url'];
        }

        return $config['notify_url'] ?? '';
    }

    protected function getAppAuthToken(array $params, array $config): string
    {
        if (!empty($params['_app_auth_token'])) {
            return $params['_app_auth_token'];
        }

        return $config['app_auth_token'] ?? '';
    }

    /**
     * @param string $tenant
     * @param array $config
     * @return string
     * @throws InvalidConfigException
     */
    protected function getAppCertSn(string $tenant, array $config): string
    {
        if (!empty($config['app_public_cert_sn'])) {
            return $config['app_public_cert_sn'];
        }

        $path = $config['app_public_cert_path'] ?? null;

        if (is_null($path)) {
            throw new InvalidConfigException(Exception::ALIPAY_CONFIG_ERROR, 'Missing Alipay Config -- [app_public_cert_path]');
        }

        $cert = file_get_contents($path);
        $ssl = openssl_x509_parse($cert);

        if (false === $ssl) {
            throw new InvalidConfigException(Exception::ALIPAY_CONFIG_ERROR, 'Parse `app_public_cert_path` Error');
        }

        $result = $this->getCertSn($ssl['issuer'] ?? [], $ssl['serialNumber'] ?? '');

        config([
            'pay.alipay.' . $tenant . '.app_public_cert_sn' => $result
        ]);

        return $result;
    }

    /**
     * @param string $tenant
     * @param array $config
     * @return string
     * @throws InvalidConfigException
     */
    protected function getAlipayRootCertSn(string $tenant, array $config): string
    {
        if (!empty($config['alipay_root_cert_sn'])) {
            return $config['alipay_root_cert_sn'];
        }

        $path = $config['alipay_root_cert_path'] ?? null;

        if (is_null($path)) {
            throw new InvalidConfigException(Exception::ALIPAY_CONFIG_ERROR, 'Missing Alipay Config -- [alipay_root_cert_path]');
        }

        $sn = '';
        $exploded = explode('-----END CERTIFICATE-----', file_get_contents($path));

        foreach ($exploded as $cert) {
            if (empty(trim($cert))) {
                continue;
            }

            $ssl = openssl_x509_parse($cert . '-----END CERTIFICATE-----');

            if (false === $ssl) {
                throw new InvalidConfigException(Exception::ALIPAY_CONFIG_ERROR, 'Invalid alipay_root_cert');
            }

            $detail = $this->formatCert($ssl);

            if ('sha1WithRSAEncryption' === $detail['signatureTypeLN'] || 'sha256WithRSAEncryption' === $detail['signatureTypeLN']) {
                $sn .= $this->getCertSn($detail['issuer'], $detail['serialNumber']) . '_';
            }
        }

        $result = substr($sn, 0, -1);

        config([
            'pay.alipay.' . $tenant . '.alipay_root_cert_sn' => $result
        ]);

        return $result;
    }

    protected function getCertSn(array $issuer, string $serialNumber): string
    {
        return md5(
            $this->array2string(array_reverse($issuer)) . $serialNumber
        );
    }

    protected function array2string(array $array): string
    {
        $string = [];

        foreach ($array as $key => $value) {
            $string[] = $key . '=' . $value;
        }

        return implode(',', $string);
    }

    protected function formatCert(array $ssl): array
    {
        if (0 === strpos($ssl['serialNumber'] ?? '', '0x')) {
            $ssl['serialNumber'] = $this->hex2dec($ssl['serialNumberHex'] ?? '');
        }

        return $ssl;
    }

    protected function hex2dec(string $hex): string
    {
        $dec = '0';
        $len = strlen($hex);

        for ($i = 1; $i <= $len; ++$i) {
            $dec = bcadd(
                $dec,
                bcmul((string)hexdec($hex[$i - 1]), bcpow('16', (string)($len - $i), 0), 0),
                0
            );
        }

        return $dec;
    }
}
