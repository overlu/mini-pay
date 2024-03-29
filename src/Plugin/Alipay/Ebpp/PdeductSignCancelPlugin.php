<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Alipay\Ebpp;

use Closure;
use MiniPay\Contract\PluginInterface;
use MiniPay\Logger;
use MiniPay\Rocket;

/**
 * Class PdeductSignCancelPlugin
 * @package MiniPay\Plugin\Alipay\Ebpp
 * @see https://opendocs.alipay.com/open/02hd34
 */
class PdeductSignCancelPlugin implements PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[alipay][PdeductSignCancelPlugin] 插件开始装载', ['rocket' => $rocket]);

        $rocket->mergePayload([
            'method' => 'alipay.ebpp.pdeduct.sign.cancel',
            'biz_content' => array_merge(
                [
                    'agent_channel' => 'PUBLICPLATFORM',
                ],
                $rocket->getParams(),
            ),
        ]);

        Logger::info('[alipay][PdeductSignCancelPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }
}
