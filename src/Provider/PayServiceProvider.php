<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Provider;

//use GuzzleHttp\Client;
use Mini\Contracts\Container\BindingResolutionException;
use Mini\Support\ServiceProvider;
use MiniPay\Contract\DirectionInterface;
use MiniPay\Contract\PackerInterface;
use MiniPay\Direction\CollectionDirection;
use MiniPay\Packer\JsonPacker;
use ReflectionException;

class PayServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            dirname(__DIR__) . '/../config/pay.php' => config_path('pay.php'),],
            'config'
        );
    }

    /**
     * @throws BindingResolutionException
     * @throws ReflectionException
     */
    public function register(): void
    {
        $this->mergeConfigFrom(dirname(__DIR__) . '/../config/pay.php', 'pay');

//        $this->app->singleton('pay.http', function () {
//            return new Client(config('pay.http', []));
//        });
        $this->app->singleton(DirectionInterface::class, CollectionDirection::class);
        $this->app->singleton(PackerInterface::class, JsonPacker::class);

        $this->app->singleton('pay.alipay', function () {
            return new Alipay();
        });

        $this->app->singleton('pay.wechat', function () {
            return new WeChat();
        });

        $this->app->singleton('pay.unipay', function () {
            return new Unipay();
        });
    }

    /**
     * @return string[]
     */
    public function provides(): array
    {
        return ['pay.alipay', 'pay.wechat', 'pay.unipay'];
    }
}
