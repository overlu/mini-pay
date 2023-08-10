<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay;

use Mini\Container\Container;
use Mini\Contracts\Container\BindingResolutionException;
use MiniPay\Contract\DirectionInterface;
use MiniPay\Contract\PackerInterface;
use MiniPay\Direction\CollectionDirection;
use MiniPay\Packer\JsonPacker;
use MiniPay\Provider\Alipay;
use MiniPay\Provider\Unipay;
use MiniPay\Provider\WeChat;
use ReflectionException;

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

    public const PAY_CHANNEL = [
        'alipay' => Alipay::class,
        'wechat' => WeChat::class,
        'unipay' => Unipay::class
    ];

    /**
     * @throws BindingResolutionException
     * @throws ReflectionException
     */
    private function __construct()
    {
        app()->singleton(DirectionInterface::class, CollectionDirection::class);
        app()->singleton(PackerInterface::class, JsonPacker::class);
    }

    /**
     * @param string $service
     * @param array $config
     * @return Container|mixed|object
     */
    public static function __callStatic(string $service, array $config = [])
    {
        return empty($config) ? app('pay.' . $service) : (new self::PAY_CHANNEL[$service]($config));
    }
}
