<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay;

use Mini\Contracts\Container\BindingResolutionException;
use Mini\Support\ServiceProvider;
use Yansongda\Pay\Exception\ContainerException;
use Yansongda\Pay\Pay;

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
     * @throws ContainerException
     */
    public function register(): void
    {
        $this->mergeConfigFrom(dirname(__DIR__) . '/config/pay.php', 'pay');

        Pay::config(config('pay'));

        $this->app->singleton('pay.alipay', function () {
            return Pay::alipay();
        });

        $this->app->singleton('pay.wechat', function () {
            return Pay::wechat();
        });

        $this->app->singleton('pay.unipay', function () {
            return Pay::unipay();
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
