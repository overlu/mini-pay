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
 * 支付中签约
 * Class ContractOrderPlugin
 * @package MiniPay\Plugin\WeChat\Papay
 * @see https://pay.weixin.qq.com/wiki/doc/api/wxpay_v2/papay/chapter3_5.shtml
 */
class ContractOrderPlugin extends GeneralV2Plugin
{
    /**
     * @param Rocket $rocket
     * @return string
     */
    protected function getUri(Rocket $rocket): string
    {
        return 'pay/contractorder';
    }
}
