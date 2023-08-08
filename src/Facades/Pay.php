<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Facades;

use Mini\Facades\Facade;
use Yansongda\Pay\Provider\Alipay;
use Yansongda\Pay\Provider\Unipay;
use Yansongda\Pay\Provider\Wechat;

class Pay extends Facade
{
    /**
     * Return the facade accessor.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'pay.alipay';
    }

    /**
     * Return the facade accessor.
     * @return Alipay
     */
    public static function alipay(): Alipay
    {
        return app('pay.alipay');
    }

    /**
     * Return the facade accessor.
     * @return Wechat
     */
    public static function wechat(): Wechat
    {
        return app('pay.wechat');
    }

    /**
     * Return the facade accessor.
     * @return Unipay
     */
    public static function unipay(): Unipay
    {
        return app('pay.unipay');
    }
}
