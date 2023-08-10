<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Alipay\Fund;

use MiniPay\Plugin\Alipay\GeneralPlugin;

/**
 * Class AuthOrderUnfreezePlugin
 * @package MiniPay\Plugin\Alipay\Fund
 * @see https://opendocs.alipay.com/open/02fkbc
 */
class AuthOrderUnfreezePlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'alipay.fund.auth.order.unfreeze';
    }
}
