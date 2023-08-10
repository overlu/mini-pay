<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Pay\Mini;

/**
 * Class InvokePrepayPlugin
 * @package MiniPay\Plugin\WeChat\Pay\Mini
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter3_5_4.shtml
 */
class InvokePrepayPlugin extends \MiniPay\Plugin\WeChat\Pay\Common\InvokePrepayPlugin
{
    protected function getConfigKey(): string
    {
        return 'mini_app_id';
    }
}
