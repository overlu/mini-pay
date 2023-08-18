<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Provider;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Mini\Facades\Request;
use Mini\Support\Collection;
use Mini\Support\Str;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use MiniPay\Event;
use MiniPay\Exception\Exception;
use MiniPay\Exception\InvalidParamsException;
use MiniPay\Pay;
use MiniPay\Plugin\ParserPlugin;
use MiniPay\Plugin\WeChat\CallbackPlugin;
use MiniPay\Plugin\WeChat\LaunchPlugin;
use MiniPay\Plugin\WeChat\PreparePlugin;
use MiniPay\Plugin\WeChat\RadarSignPlugin;

/**
 * Class WeChat
 * @package MiniPay\Provider
 * @method Collection app(array $order)           APP 支付
 * @method Collection mini(array $order)          小程序支付
 * @method Collection mp(array $order)            公众号支付
 * @method Collection scan(array $order)          扫码支付
 * @method Collection wap(array $order)           H5 支付
 * @method Collection transfer(array $order)      帐户转账
 * @method Collection papay(array $order)         支付时签约（委托代扣）
 * @method Collection papayApply(array $order)    申请代扣（委托代扣）
 * @method Collection papayContract(array $order) 申请代扣（委托代扣）
 */
class WeChat extends AbstractProvider
{
    public const AUTH_TAG_LENGTH_BYTE = 16;

    public const MCH_SECRET_KEY_LENGTH_BYTE = 32;

    public const URL = [
        Pay::MODE_NORMAL => 'https://api.mch.weixin.qq.com/',
        Pay::MODE_SANDBOX => 'https://api.mch.weixin.qq.com/sandboxnew/',
        Pay::MODE_SERVICE => 'https://api.mch.weixin.qq.com/',
    ];

    /**
     * @param string $shortcut
     * @param array $params
     * @return array|Collection|MessageInterface|null
     * @throws InvalidParamsException
     */
    public function __call(string $shortcut, array $params)
    {
        $plugin = '\\MiniPay\\Plugin\\WeChat\\Shortcut\\' .
            Str::studly($shortcut) . 'Shortcut';

        return $this->call($plugin, ...$params);
    }

    /**
     * @param array|string $order
     * @return array|Collection|MessageInterface|null
     * @throws InvalidParamsException
     */
    public function find($order)
    {
        $order = is_array($order) ? $order : ['transaction_id' => $order];

        Event::dispatch(new Event\MethodCalled('wechat', __METHOD__, $order, null));

        return $this->__call('query', [$order]);
    }

    /**
     * @param array|string $order
     * @throws InvalidParamsException
     */
    public function cancel($order): void
    {
        throw new InvalidParamsException(Exception::METHOD_NOT_SUPPORTED, 'WeChat does not support cancel api');
    }

    /**
     * @param array|string $order
     * @throws InvalidParamsException
     */
    public function close($order): void
    {
        $order = is_array($order) ? $order : ['out_trade_no' => $order];

        Event::dispatch(new Event\MethodCalled('wechat', __METHOD__, $order, null));

        $this->__call('close', [$order]);
    }

    /**
     * @param array $order
     * @return array|Collection|MessageInterface|null
     * @throws InvalidParamsException
     */
    public function refund(array $order)
    {
        Event::dispatch(new Event\MethodCalled('wechat', __METHOD__, $order, null));

        return $this->__call('refund', [$order]);
    }

    /**
     * @param null $contents
     * @param array|null $params
     * @return Collection
     * @throws InvalidParamsException
     */
    public function callback($contents = null, ?array $params = null): Collection
    {
        $request = $this->getCallbackParams($contents);

        Event::dispatch(new Event\CallbackReceived('wechat', clone $request, $params, null));

        return $this->pay(
            [CallbackPlugin::class],
            ['request' => $request, 'params' => $params]
        );
    }

    public function success(): ResponseInterface
    {
        return new Response(
            200,
            ['Content-Type' => 'application/json'],
            json_encode(['code' => 'SUCCESS', 'message' => '成功']),
        );
    }

    public function mergeCommonPlugins(array $plugins): array
    {
        return array_merge(
            [PreparePlugin::class],
            $plugins,
            [RadarSignPlugin::class],
            [LaunchPlugin::class, ParserPlugin::class],
        );
    }

    /**
     * @param null $contents
     * @return ServerRequestInterface
     */
    protected function getCallbackParams($contents = null): ServerRequestInterface
    {
        if (is_array($contents) && isset($contents['body'], $contents['headers'])) {
            return new ServerRequest('POST', 'http://localhost', $contents['headers'], $contents['body']);
        }

        if (is_array($contents)) {
            return new ServerRequest('POST', 'http://localhost', [], json_encode($contents));
        }

        if ($contents instanceof ServerRequestInterface) {
            return $contents;
        }

        return request();
    }
}
