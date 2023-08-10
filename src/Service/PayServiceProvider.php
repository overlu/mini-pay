<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Service;

//use GuzzleHttp\Client;
use Mini\Contracts\Container\BindingResolutionException;
use Mini\Support\ServiceProvider;
use MiniPay\Provider\Alipay;
use MiniPay\Provider\Unipay;
use MiniPay\Provider\WeChat;
use ReflectionException;

class PayServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            dirname(__DIR__) . '/config/pay.php' => config_path('pay.php'),],
            'pay'
        );
    }

    /**
     * @throws BindingResolutionException
     * @throws ReflectionException
     */
    public function register(): void
    {
        $this->mergeConfigFrom(dirname(__DIR__) . '/config/pay.php', 'pay');

//        $this->app->singleton('pay.http', function () {
//            return new Client(config('pay.http', []));
//        });

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
