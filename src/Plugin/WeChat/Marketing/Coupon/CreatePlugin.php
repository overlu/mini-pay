<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Marketing\Coupon;

use MiniPay\Plugin\WeChat\GeneralPlugin;
use MiniPay\Rocket;

use function MiniPay\get_wechat_config;

/**
 * Class CreatePlugin
 * @package MiniPay\Plugin\WeChat\Marketing\Coupon
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter9_1_1.shtml
 */
class CreatePlugin extends GeneralPlugin
{
    /**
     * @param Rocket $rocket
     */
    protected function doSomething(Rocket $rocket): void
    {
        $config = get_wechat_config($rocket->getParams());
        $payload = $rocket->getPayload();

        if (!$payload->has('belong_merchant')) {
            $rocket->mergePayload(['belong_merchant' => $config['mch_id']]);
        }
    }

    protected function getUri(Rocket $rocket): string
    {
        return 'v3/marketing/favor/coupon-stocks';
    }
}
