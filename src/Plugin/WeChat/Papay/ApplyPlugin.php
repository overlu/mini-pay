<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Papay;

use MiniPay\Plugin\WeChat\GeneralV2Plugin;
use MiniPay\Rocket;

/**
 * 申请代扣
 * Class ApplyPlugin
 * @package MiniPay\Plugin\WeChat\Papay
 * @see https://pay.weixin.qq.com/wiki/doc/api/wxpay_v2/papay/chapter3_8.shtml
 */
class ApplyPlugin extends GeneralV2Plugin
{
    /**
     * @param Rocket $rocket
     * @return string
     */
    protected function getUri(Rocket $rocket): string
    {
        return 'pay/pappayapply';
    }
}
