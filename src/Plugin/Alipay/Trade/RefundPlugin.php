<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Alipay\Trade;

use MiniPay\Plugin\Alipay\GeneralPlugin;

/**
 * Class RefundPlugin
 * @package MiniPay\Plugin\Alipay\Trade
 * @see https://opendocs.alipay.com/open/02ekfk
 */
class RefundPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'alipay.trade.refund';
    }
}
