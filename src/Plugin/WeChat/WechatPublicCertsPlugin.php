<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat;

use MiniPay\Rocket;

/**
 * Class WechatPublicCertsPlugin
 * @package MiniPay\Plugin\WeChat
 */
class WechatPublicCertsPlugin extends GeneralPlugin
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
        return 'v3/certificates';
    }
}
