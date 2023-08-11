<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay;

use Mini\Container\Container;
use MiniPay\Provider\Alipay;
use MiniPay\Provider\Unipay;
use MiniPay\Provider\WeChat;

/**
 * Class Pay
 * @package MiniPay
 * @method static Alipay alipay(array $config = [])
 * @method static WeChat wechat(array $config = [])
 * @method static Unipay unipay(array $config = [])
 */
class Pay
{
    /**
     * 正常模式.
     */
    public const MODE_NORMAL = 0;

    /**
     * 沙箱模式.
     */
    public const MODE_SANDBOX = 1;

    /**
     * 服务商模式.
     */
    public const MODE_SERVICE = 2;

    public static array $channel = [
        'alipay' => Alipay::class,
        'wechat' => WeChat::class,
        'unipay' => Unipay::class
    ];

    /**
     * @param string $service
     * @param array $config
     * @return Container|mixed|object
     */
    public static function __callStatic(string $service, array $config = [])
    {
        return empty($config) ? app('pay.' . $service) : (new self::$channel[$service]($config));
    }
}
