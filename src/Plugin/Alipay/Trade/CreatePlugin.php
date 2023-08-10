<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Alipay\Trade;

use MiniPay\Plugin\Alipay\GeneralPlugin;

/**
 * Class CreatePlugin
 * @package MiniPay\Plugin\Alipay\Trade
 * @see https://opendocs.alipay.com/open/02ekfj
 */
class CreatePlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'alipay.trade.create';
    }
}
