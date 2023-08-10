<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Alipay\Trade;

use MiniPay\Plugin\Alipay\GeneralPlugin;

/**
 * Class QueryPlugin
 * @package MiniPay\Plugin\Alipay\Trade
 * @see https://opendocs.alipay.com/open/02ekfh?scene=common
 */
class QueryPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'alipay.trade.query';
    }
}
