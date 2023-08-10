<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Unipay;

use Closure;
use Mini\Support\Collection;
use MiniPay\Contract\PluginInterface;
use MiniPay\Exception\InvalidConfigException;
use MiniPay\Exception\InvalidResponseException;
use MiniPay\Logger;
use MiniPay\Rocket;

use function MiniPay\should_do_http_request;
use function MiniPay\verify_unipay_sign;

/**
 * Class LaunchPlugin
 * @package MiniPay\Plugin\Unipay
 */
class LaunchPlugin implements PluginInterface
{
    /**
     * @param Rocket $rocket
     * @param Closure $next
     * @return Rocket
     * @throws InvalidConfigException
     * @throws InvalidResponseException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        /* @var Rocket $rocket */
        $rocket = $next($rocket);

        Logger::debug('[unipay][LaunchPlugin] 插件开始装载', ['rocket' => $rocket]);

        if (should_do_http_request($rocket->getDirection())) {
            $response = Collection::wrap($rocket->getDestination());
            $signature = $response->get('signature');
            $response->forget('signature');

            verify_unipay_sign(
                $rocket->getParams(),
                $response->sortKeys()->toString(),
                $signature
            );

            $rocket->setDestination($response);
        }

        Logger::info('[unipay][LaunchPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $rocket;
    }
}
