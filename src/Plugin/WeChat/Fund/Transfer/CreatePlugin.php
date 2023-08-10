<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Fund\Transfer;

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
 * Class CreatePlugin
 * @package MiniPay\Plugin\WeChat\Fund\Transfer
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter4_3_1.shtml
 */
class CreatePlugin extends GeneralPlugin
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
        $extra = $this->getWechatId($params, $rocket->getPayload());

        if (!empty($params['transfer_detail_list'][0]['user_name'] ?? '')) {
            $params = $this->loadSerialNo($params);

            $rocket->setParams($params);

            $extra['transfer_detail_list'] = $this->getEncryptUserName($params);
        }

        $rocket->mergePayload($extra);
    }

    protected function getUri(Rocket $rocket): string
    {
        return 'v3/transfer/batches';
    }

    protected function getPartnerUri(Rocket $rocket): string
    {
        return 'v3/partner-transfer/batches';
    }

    /**
     * @param array $params
     * @param Collection $payload
     * @return array
     */
    protected function getWechatId(array $params, Collection $payload): array
    {
        $config = get_wechat_config($params);
        $key = $this->getConfigKey($params);

        $appId = [
            'appid' => $payload->get('appid', $config[$key] ?? ''),
        ];

        if (Pay::MODE_SERVICE === ($config['mode'] ?? null)) {
            $appId = [
                'sub_mchid' => $payload->get('sub_mchid', $config['sub_mch_id'] ?? ''),
            ];
        }

        return $appId;
    }

    /**
     * @param array $params
     * @return array
     * @throws InvalidParamsException
     */
    protected function getEncryptUserName(array $params): array
    {
        $lists = $params['transfer_detail_list'] ?? [];
        $publicKey = $this->getPublicKey($params, $params['_serial_no'] ?? '');

        foreach ($lists as $key => $list) {
            $lists[$key]['user_name'] = encrypt_wechat_contents($list['user_name'], $publicKey);
        }

        return $lists;
    }
}
