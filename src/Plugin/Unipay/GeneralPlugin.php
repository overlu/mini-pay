<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Unipay;

use Closure;
use MiniPay\Contract\PluginInterface;
use MiniPay\Logger;
use MiniPay\Pay;
use MiniPay\Provider\Unipay;
use MiniPay\Request;
use MiniPay\Rocket;

use function MiniPay\get_unipay_config;

/**
 * Class GeneralPlugin
 * @package MiniPay\Plugin\Unipay
 */
abstract class GeneralPlugin implements PluginInterface
{
    /**
     * @param Rocket $rocket
     * @param Closure $next
     * @return Rocket
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        Logger::debug('[unipay][GeneralPlugin] 通用插件开始装载', ['rocket' => $rocket]);

        $rocket->setRadar($this->getRequest($rocket));
        $this->doSomething($rocket);

        Logger::info('[unipay][GeneralPlugin] 通用插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    /**
     * @param Rocket $rocket
     * @return Request
     */
    protected function getRequest(Rocket $rocket): Request
    {
        return new Request(
            $this->getMethod(),
            $this->getUrl($rocket),
            $this->getHeaders(),
        );
    }

    protected function getMethod(): string
    {
        return 'POST';
    }

    /**
     * @param Rocket $rocket
     * @return string
     */
    protected function getUrl(Rocket $rocket): string
    {
        $url = $this->getUri($rocket);

        if (0 === strpos($url, 'http')) {
            return $url;
        }

        $config = get_unipay_config($rocket->getParams());

        return Unipay::URL[$config['mode'] ?? Pay::MODE_NORMAL] . $url;
    }

    protected function getHeaders(): array
    {
        return [
            'User-Agent' => 'mini-pay/pay-v3',
            'Content-Type' => 'application/x-www-form-urlencoded;charset=utf-8',
        ];
    }

    abstract protected function doSomething(Rocket $rocket): void;

    abstract protected function getUri(Rocket $rocket): string;
}
