<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Pay\Combine;

use MiniPay\Plugin\WeChat\Pay\Common\CombinePrepayPlugin;
use MiniPay\Rocket;

/**
 * Class AppPrepayPlugin
 * @package MiniPay\Plugin\WeChat\Pay\Combine
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter5_1_1.shtml
 */
class AppPrepayPlugin extends CombinePrepayPlugin
{
    /**
     * @param Rocket $rocket
     * @return string
     */
    protected function getUri(Rocket $rocket): string
    {
        return 'v3/combine-transactions/app';
    }
}
