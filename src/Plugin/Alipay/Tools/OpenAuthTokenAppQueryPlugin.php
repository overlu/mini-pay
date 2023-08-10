<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Alipay\Tools;

use MiniPay\Plugin\Alipay\GeneralPlugin;

/**
 * Class OpenAuthTokenAppQueryPlugin
 * @package MiniPay\Plugin\Alipay\Tools
 * @see https://opendocs.alipay.com/isv/03l8ca
 */
class OpenAuthTokenAppQueryPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'alipay.open.auth.token.app.query';
    }
}
