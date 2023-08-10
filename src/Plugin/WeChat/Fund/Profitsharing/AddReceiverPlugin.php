<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Fund\Profitsharing;

use Mini\Support\Collection;
use MiniPay\Exception\InvalidConfigException;
use MiniPay\Exception\InvalidParamsException;
use MiniPay\Exception\InvalidResponseException;
use MiniPay\Pay;
use MiniPay\Plugin\WeChat\GeneralPlugin;
use MiniPay\Rocket;
use MiniPay\Traits\HasWechatEncryption;

use function MiniPay\encrypt_wechat_contents;
use function MiniPay\get_wechat_config;

/**
 * Class AddReceiverPlugin
 * @package MiniPay\Plugin\WeChat\Fund\Profitsharing
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter8_1_8.shtml
 */
class AddReceiverPlugin extends GeneralPlugin
{
    use HasWechatEncryption;

    /**
     * @param Rocket $rocket
     * @throws InvalidConfigException
     * @throws InvalidParamsException
     * @throws InvalidResponseException
     */
    protected function doSomething(Rocket $rocket): void
    {
        $params = $rocket->getParams();
        $config = get_wechat_config($rocket->getParams());
        $extra = $this->getWechatId($config, $rocket->getPayload());

        if (!empty($params['name'] ?? '')) {
            $params = $this->loadSerialNo($params);

            $name = $this->getEncryptUserName($params);
            $params['name'] = $name;
            $extra['name'] = $name;
            $rocket->setParams($params);
        }

        $rocket->mergePayload($extra);
    }

    protected function getUri(Rocket $rocket): string
    {
        return 'v3/profitsharing/receivers/add';
    }

    protected function getWechatId(array $config, Collection $payload): array
    {
        $wechatId = [
            'appid' => $config['mp_app_id'] ?? null,
        ];

        if (Pay::MODE_SERVICE === ($config['mode'] ?? null)) {
            $wechatId['sub_mchid'] = $payload->get('sub_mchid', $config['sub_mch_id'] ?? '');
        }

        return $wechatId;
    }

    /**
     * @param array $params
     * @return string
     * @throws InvalidParamsException
     */
    protected function getEncryptUserName(array $params): string
    {
        $name = $params['name'] ?? '';
        $publicKey = $this->getPublicKey($params, $params['_serial_no'] ?? '');

        return encrypt_wechat_contents($name, $publicKey);
    }
}
