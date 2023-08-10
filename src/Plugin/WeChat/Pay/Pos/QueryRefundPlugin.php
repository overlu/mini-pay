<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Pay\Pos;

use MiniPay\Plugin\WeChat\GeneralV2Plugin;
use MiniPay\Rocket;

/**
 * Class QueryRefundPlugin
 * @package MiniPay\Plugin\WeChat\Pay\Pos
 * @see https://pay.weixin.qq.com/wiki/doc/api/micropay.php?chapter=9_5
 */
class QueryRefundPlugin extends GeneralV2Plugin
{
    protected function getUri(Rocket $rocket): string
    {
        return 'pay/refundquery';
    }
}
