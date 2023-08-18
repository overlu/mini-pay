<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Provider;

use GuzzleHttp\Psr7\Response;
use Mini\Facades\Request;
use Mini\Support\Collection;
use Mini\Support\Str;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use MiniPay\Event;
use MiniPay\Exception\InvalidParamsException;
use MiniPay\Pay;
use MiniPay\Plugin\Alipay\CallbackPlugin;
use MiniPay\Plugin\Alipay\LaunchPlugin;
use MiniPay\Plugin\Alipay\PreparePlugin;
use MiniPay\Plugin\Alipay\RadarSignPlugin;
use MiniPay\Plugin\ParserPlugin;

/**
 * Class Alipay
 * @package MiniPay\Provider
 * @method ResponseInterface app(array $order)      APP 支付
 * @method Collection        pos(array $order)      刷卡支付
 * @method Collection        scan(array $order)     扫码支付
 * @method Collection        transfer(array $order) 帐户转账
 * @method ResponseInterface wap(array $order)      手机网站支付
 * @method ResponseInterface web(array $order)      电脑支付
 * @method Collection        mini(array $order)     小程序支付
 */
class Alipay extends AbstractProvider
{
    public const URL = [
        Pay::MODE_NORMAL => 'https://openapi.alipay.com/gateway.do?charset=utf-8',
        Pay::MODE_SANDBOX => 'https://openapi-sandbox.dl.alipaydev.com/gateway.do?charset=utf-8',
        Pay::MODE_SERVICE => 'https://openapi.alipay.com/gateway.do?charset=utf-8',
    ];

    /**
     * @param string $shortcut
     * @param array $params
     * @return array|Collection|MessageInterface|null
     * @throws InvalidParamsException
     */
    public function __call(string $shortcut, array $params)
    {
        $plugin = '\\MiniPay\\Plugin\\Alipay\\Shortcut\\' .
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
        $order = is_array($order) ? $order : ['out_trade_no' => $order];

        Event::dispatch(new Event\MethodCalled('alipay', __METHOD__, $order, null));

        return $this->__call('query', [$order]);
    }

    /**
     * @param array|string $order
     * @return array|Collection|MessageInterface|null
     * @throws InvalidParamsException
     */
    public function cancel($order)
    {
        $order = is_array($order) ? $order : ['out_trade_no' => $order];

        Event::dispatch(new Event\MethodCalled('alipay', __METHOD__, $order, null));

        return $this->__call('cancel', [$order]);
    }

    /**
     * @param array|string $order
     * @return array|Collection|MessageInterface|null
     * @throws InvalidParamsException
     */
    public function close($order)
    {
        $order = is_array($order) ? $order : ['out_trade_no' => $order];

        Event::dispatch(new Event\MethodCalled('alipay', __METHOD__, $order, null));

        return $this->__call('close', [$order]);
    }

    /**
     * @param array $order
     * @return array|Collection|MessageInterface|null
     * @throws InvalidParamsException
     */
    public function refund(array $order)
    {
        Event::dispatch(new Event\MethodCalled('alipay', __METHOD__, $order, null));

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

        Event::dispatch(new Event\CallbackReceived('alipay', $request->all(), $params, null));

        return $this->pay(
            [CallbackPlugin::class],
            $request->merge($params)->all()
        );
    }

    public function success(): ResponseInterface
    {
        return new Response(200, [], 'success');
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
     * @param null|array|ServerRequestInterface $contents
     */
    protected function getCallbackParams($contents = null): Collection
    {
        if (is_array($contents)) {
            return Collection::wrap($contents);
        }

        if ($contents instanceof ServerRequestInterface) {
            return Collection::wrap('GET' === $contents->getMethod() ? $contents->getQueryParams() :
                $contents->getParsedBody());
        }

        return Collection::wrap(
            array_merge(Request::getQueryParams(), Request::getParsedBody())
        );
    }
}
