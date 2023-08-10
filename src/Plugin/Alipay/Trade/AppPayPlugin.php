<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Alipay\Trade;

use Closure;
use MiniPay\Contract\PluginInterface;
use MiniPay\Direction\ResponseDirection;
use MiniPay\Logger;
use MiniPay\Rocket;
use MiniPay\Traits\SupportServiceProviderTrait;

/**
 * Class AppPayPlugin
 * @package MiniPay\Plugin\Alipay\Trade
 * @see https://opendocs.alipay.com/open/02e7gq?scene=common
 */
class AppPayPlugin implements PluginInterface
{
    use SupportServiceProviderTrait;

    /**
     * @param Rocket $rocket
     * @param Closure $next
     * @return Rocket
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[alipay][AppPayPlugin] 插件开始装载', ['rocket' => $rocket]);

        $this->loadAlipayServiceProvider($rocket);

        $rocket->setDirection(ResponseDirection::class)
            ->mergePayload([
                'method' => 'alipay.trade.app.pay',
                'biz_content' => array_merge(
                    ['product_code' => 'QUICK_MSECURITY_PAY'],
                    $rocket->getParams(),
                ),
            ]);

        Logger::info('[alipay][AppPayPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
