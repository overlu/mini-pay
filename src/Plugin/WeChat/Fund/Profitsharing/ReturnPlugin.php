<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Fund\Profitsharing;

use MiniPay\Pay;
use MiniPay\Plugin\WeChat\GeneralPlugin;
use MiniPay\Rocket;

use function MiniPay\get_wechat_config;

/**
 * Class ReturnPlugin
 * @package MiniPay\Plugin\WeChat\Fund\Profitsharing
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter8_1_3.shtml
 */
class ReturnPlugin extends GeneralPlugin
{
    /**
     * @param Rocket $rocket
     */
    protected function doSomething(Rocket $rocket): void
    {
        $config = get_wechat_config($rocket->getParams());

        if (Pay::MODE_SERVICE === ($config['mode'] ?? null)) {
            $rocket->mergePayload([
                'sub_mchid' => $rocket->getPayload()
                    ->get('sub_mchid', $config['sub_mch_id'] ?? ''),
            ]);
        }
    }

    protected function getUri(Rocket $rocket): string
    {
        return 'v3/profitsharing/return-orders';
    }
}
