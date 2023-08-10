<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Pay\H5;

use MiniPay\Rocket;

/**
 * Class PrepayPlugin
 * @package MiniPay\Plugin\WeChat\Pay\H5
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter3_3_1.shtml
 */
class PrepayPlugin extends \MiniPay\Plugin\WeChat\Pay\Common\PrepayPlugin
{
    protected function getUri(Rocket $rocket): string
    {
        return 'v3/pay/transactions/h5';
    }

    protected function getPartnerUri(Rocket $rocket): string
    {
        return 'v3/pay/partner/transactions/h5';
    }
}
