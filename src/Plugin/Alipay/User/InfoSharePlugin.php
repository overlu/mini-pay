<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Alipay\User;

use Closure;
use MiniPay\Contract\PluginInterface;
use MiniPay\Logger;
use MiniPay\Rocket;

/**
 * Class InfoSharePlugin
 * @package MiniPay\Plugin\Alipay\User
 * @see https://opendocs.alipay.com/open/02aild
 */
class InfoSharePlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[alipay][InfoSharePlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->mergePayload([
            'method' => 'alipay.user.info.share',
            'auth_token' => $rocket->getParams()['auth_token'] ?? '',
        ]);

        Logger::info('[alipay][InfoSharePlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
