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
 * Class QueryCallbackPlugin
 * @package MiniPay\Plugin\WeChat\Risk\Complaints
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter10_2_3.shtml
 */
class QueryCallbackPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'GET';
    }

    protected function doSomething(Rocket $rocket): void
    {
        $rocket->setPayload(null);
    }

    protected function getUri(Rocket $rocket): string
    {
        return 'v3/merchant-service/complaint-notifications';
    }
}
