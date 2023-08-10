<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Marketing\Coupon;

use MiniPay\Exception\Exception;
use MiniPay\Exception\InvalidParamsException;
use MiniPay\Plugin\WeChat\GeneralPlugin;
use MiniPay\Rocket;

use function MiniPay\get_wechat_config;

/**
 * Class SendPlugin
 * @package MiniPay\Plugin\WeChat\Marketing\Coupon
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter9_1_2.shtml
 */
class SendPlugin extends GeneralPlugin
{
    /**
     * @param Rocket $rocket
     */
    protected function doSomething(Rocket $rocket): void
    {
        $params = $rocket->getParams();
        $config = get_wechat_config($params);

        if (!$rocket->getPayload()->has('appid')) {
            $rocket->mergePayload(['appid' => $config[$this->getConfigKey($params)] ?? '']);
        }

        if (!$rocket->getPayload()->has('stock_creator_mchid')) {
            $rocket->mergePayload(['stock_creator_mchid' => $config['mch_id']]);
        }

        $rocket->getPayload()->forget('openid');
    }

    /**
     * @throws InvalidParamsException
     */
    protected function getUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();

        if (!$payload->has('openid')) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        return 'v3/marketing/favor/users/' . $payload->get('openid') . '/coupons';
    }
}
