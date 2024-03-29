<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat;

use Closure;
use Psr\Http\Message\RequestInterface;
use MiniPay\Contract\PluginInterface;
use MiniPay\Logger;
use MiniPay\Pay;
use MiniPay\Request;
use MiniPay\Rocket;

use function MiniPay\get_wechat_base_uri;
use function MiniPay\get_wechat_config;

/**
 * Class GeneralPlugin
 * @package MiniPay\Plugin\WeChat
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
        Logger::debug('[wechat][GeneralPlugin] 通用插件开始装载', ['rocket' => $rocket]);

        $rocket->setRadar($this->getRequest($rocket));
        $this->doSomething($rocket);

        Logger::info('[wechat][GeneralPlugin] 通用插件装载完毕', ['rocket' => $rocket]);

        return $next($rocket);
    }

    /**
     * @param Rocket $rocket
     * @return RequestInterface
     */
    protected function getRequest(Rocket $rocket): RequestInterface
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
        $params = $rocket->getParams();

        $url = Pay::MODE_SERVICE === (get_wechat_config($params)['mode'] ?? null) ? $this->getPartnerUri($rocket) : $this->getUri($rocket);

        return 0 === strpos($url, 'http') ? $url : (get_wechat_base_uri($params) . $url);
    }

    protected function getHeaders(): array
    {
        return [
            'Accept' => 'application/json, text/plain, application/x-gzip',
            'User-Agent' => 'mini-pay/pay-v3',
            'Content-Type' => 'application/json; charset=utf-8',
        ];
    }

    protected function getConfigKey(array $params): string
    {
        $key = ($params['_type'] ?? 'mp') . '_app_id';

        if ('app_app_id' === $key) {
            $key = 'app_id';
        }

        return $key;
    }

    abstract protected function doSomething(Rocket $rocket): void;

    abstract protected function getUri(Rocket $rocket): string;

    protected function getPartnerUri(Rocket $rocket): string
    {
        return $this->getUri($rocket);
    }
}
