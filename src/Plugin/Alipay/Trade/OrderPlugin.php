<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Alipay\Trade;

use MiniPay\Plugin\Alipay\GeneralPlugin;

/**
 * Class OrderPlugin
 * @package MiniPay\Plugin\Alipay\Trade
 */
class OrderPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'alipay.trade.order.pay';
    }
}
