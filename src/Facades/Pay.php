<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Facades;

use Mini\Facades\Facade;
use MiniPay\Provider\Alipay;
use MiniPay\Provider\Unipay;
use MiniPay\Provider\WeChat;

/**
 * Class Pay
 * @package MiniPay\Facades
 */
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
     * @return WeChat
     */
    public static function wechat(): WeChat
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
