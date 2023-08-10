<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Alipay\Fund;

use MiniPay\Plugin\Alipay\GeneralPlugin;

/**
 * Class TransTobankTransferPlugin
 * @package MiniPay\Plugin\Alipay\Fund
 */
class TransTobankTransferPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'alipay.fund.trans.tobank.transfer';
    }
}
