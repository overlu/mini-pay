<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Ecommerce\Refund;

use MiniPay\Exception\Exception;
use MiniPay\Exception\InvalidParamsException;
use MiniPay\Plugin\WeChat\GeneralPlugin;
use MiniPay\Rocket;

use function MiniPay\get_wechat_config;

/**
 * Class ApplyPlugin
 * @package MiniPay\Plugin\WeChat\Ecommerce\Refund
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3_partner/apis/chapter7_6_1.shtml
 */
class ApplyPlugin extends GeneralPlugin
{
    /**
     * @param Rocket $rocket
     * @return string
     * @throws InvalidParamsException
     */
    protected function getUri(Rocket $rocket): string
    {
        throw new InvalidParamsException(Exception::NOT_IN_SERVICE_MODE);
    }

    /**
     * @param Rocket $rocket
     * @return string
     */
    protected function getPartnerUri(Rocket $rocket): string
    {
        return 'v3/ecommerce/refunds/apply';
    }

    /**
     * @param Rocket $rocket
     */
    protected function doSomething(Rocket $rocket): void
    {
        $config = get_wechat_config($rocket->getParams());
        $payload = $rocket->getPayload();
        $key = $this->getConfigKey($rocket->getParams());

        $wechatId = [
            'sub_mchid' => $payload->get('sub_mchid', $config['sub_mch_id'] ?? ''),
            'sp_appid' => $payload->get('sp_appid', $config[$key] ?? ''),
        ];

        if (!$payload->has('notify_url')) {
            $wechatId['notify_url'] = $config['notify_url'] ?? null;
        }

        $rocket->mergePayload($wechatId);
    }
}
