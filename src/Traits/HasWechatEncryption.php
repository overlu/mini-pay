<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Traits;

use MiniPay\Exception\Exception;
use MiniPay\Exception\InvalidConfigException;
use MiniPay\Exception\InvalidParamsException;
use MiniPay\Exception\InvalidResponseException;

use function MiniPay\get_wechat_config;
use function MiniPay\reload_wechat_public_certs;

/**
 * Class HasWechatEncryption
 * @package MiniPay\Traits
 */
trait HasWechatEncryption
{
    /**
     * @param array $params
     * @return array
     * @throws InvalidConfigException
     * @throws InvalidResponseException
     */
    public function loadSerialNo(array $params): array
    {
        $config = get_wechat_config($params);

        if (empty($config['wechat_public_cert_path'])) {
            reload_wechat_public_certs($params);

            $config = get_wechat_config($params);
        }

        if (empty($params['_serial_no'])) {
            mt_srand();
            $params['_serial_no'] = (string)array_rand($config['wechat_public_cert_path']);
        }

        return $params;
    }

    /**
     * @param array $params
     * @param string $serialNo
     * @return string
     * @throws InvalidParamsException
     */
    public function getPublicKey(array $params, string $serialNo): string
    {
        $config = get_wechat_config($params);

        $publicKey = $config['wechat_public_cert_path'][$serialNo] ?? null;

        if (empty($publicKey)) {
            throw new InvalidParamsException(Exception::WECHAT_SERIAL_NO_NOT_FOUND, 'WeChat serial no not found: ' . $serialNo);
        }

        return $publicKey;
    }
}
