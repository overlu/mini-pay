<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Pay\App;

use MiniPay\Rocket;

/**
 * Class PrepayPlugin
 * @package MiniPay\Plugin\WeChat\Pay\App
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter3_2_1.shtml
 */
class PrepayPlugin extends \MiniPay\Plugin\WeChat\Pay\Common\PrepayPlugin
{
    protected function getUri(Rocket $rocket): string
    {
        return 'v3/pay/transactions/app';
    }

    protected function getPartnerUri(Rocket $rocket): string
    {
        return 'v3/pay/partner/transactions/app';
    }

    protected function getConfigKey(array $params): string
    {
        return 'app_id';
    }
}
