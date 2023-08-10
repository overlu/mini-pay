<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Alipay\Ebpp;

use MiniPay\Plugin\Alipay\GeneralPlugin;

/**
 * Class PdeductBillStatusPlugin
 * @package MiniPay\Plugin\Alipay\Ebpp
 * @see https://opendocs.alipay.com/open/02hd36
 */
class PdeductBillStatusPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'alipay.ebpp.pdeduct.bill.pay.status';
    }
}
