<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay;

use Mini\Contracts\Container\BindingResolutionException;
use Mini\Contracts\Support\DeferrableProvider;
use Mini\Support\ServiceProvider;
use ReflectionException;
use Yansongda\Pay\Exception\ContainerDependencyException;
use Yansongda\Pay\Exception\ContainerException;
use Yansongda\Pay\Exception\ServiceNotFoundException;
use Yansongda\Pay\Pay;

class PayServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Boot the service.
     *
     * @author yansongda <me@yansongda.cn>
     */
    public function boot(): void
    {
        $this->publishes([
            dirname(__DIR__) . '/config/pay.php' => config_path('pay.php'),],
            'pay'
        );
    }

    /**
     * Register the service.
     * @throws ContainerException
     * @throws ServiceNotFoundException
     * @throws BindingResolutionException
     * @throws ReflectionException
     * @throws ContainerDependencyException
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
    }

    /**
     * Get services.
     *
     * @return array
     */
    public function provides(): array
    {
        return ['pay.alipay', 'pay.wechat'];
    }
}
