<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Pay\Mini;

/**
 * Class InvokePrepayV2Plugin
 * @package MiniPay\Plugin\WeChat\Pay\Mini
 * @see https://pay.weixin.qq.com/wiki/doc/api/wxa/wxa_api.php?chapter=7_7&index=5
 */
class InvokePrepayV2Plugin extends \MiniPay\Plugin\WeChat\Pay\Common\InvokePrepayV2Plugin
{
    protected function getConfigKey(): string
    {
        return 'mini_app_id';
    }
}
