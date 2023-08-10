<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Risk\Complaints;

use MiniPay\Plugin\WeChat\GeneralPlugin;
use MiniPay\Rocket;

/**
 * Class SetCallbackPlugin
 * @package MiniPay\Plugin\WeChat\Risk\Complaints
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter10_2_2.shtml
 */
class SetCallbackPlugin extends GeneralPlugin
{
    protected function doSomething(Rocket $rocket): void
    {
    }

    protected function getUri(Rocket $rocket): string
    {
        return 'v3/merchant-service/complaint-notifications';
    }
}
