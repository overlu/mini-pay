<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Alipay\Trade;

use MiniPay\Plugin\Alipay\GeneralPlugin;
use MiniPay\Rocket;
use MiniPay\Traits\SupportServiceProviderTrait;

/**
 * Class PreCreatePlugin
 * @package MiniPay\Plugin\Alipay\Trade
 * @see https://opendocs.alipay.com/open/02ekfg?scene=common
 */
class PreCreatePlugin extends GeneralPlugin
{
    use SupportServiceProviderTrait;

    /**
     * @param Rocket $rocket
     */
    protected function doSomethingBefore(Rocket $rocket): void
    {
        $this->loadAlipayServiceProvider($rocket);
    }

    protected function getMethod(): string
    {
        return 'alipay.trade.precreate';
    }
}
