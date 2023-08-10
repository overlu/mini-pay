<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Alipay\Data;

use MiniPay\Plugin\Alipay\GeneralPlugin;

/**
 * Class BillEreceiptQueryPlugin
 * @package MiniPay\Plugin\Alipay\Data
 * @see https://opendocs.alipay.com/open/029i7e
 */
class BillEreceiptQueryPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'alipay.data.bill.ereceipt.query';
    }
}
