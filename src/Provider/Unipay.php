<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Provider;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
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
use MiniPay\Plugin\Unipay\CallbackPlugin;
use MiniPay\Plugin\Unipay\LaunchPlugin;
use MiniPay\Plugin\Unipay\PreparePlugin;
use MiniPay\Plugin\Unipay\RadarSignPlugin;

/**
 * Class Unipay
 * @package MiniPay\Provider
 * @method ResponseInterface web(array $order) 电脑支付
 */
class Unipay extends AbstractProvider
{
    public const URL = [
        Pay::MODE_NORMAL => 'https://gateway.95516.com/',
        Pay::MODE_SANDBOX => 'https://gateway.test.95516.com/',
        Pay::MODE_SERVICE => 'https://gateway.95516.com',
    ];

    /**
     * @param string $shortcut
     * @param array $params
     * @return array|Collection|MessageInterface|null
     * @throws InvalidParamsException
     */
    public function __call(string $shortcut, array $params)
    {
        $plugin = '\\MiniPay\\Plugin\\Unipay\\Shortcut\\' .
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
        if (!is_array($order)) {
            throw new InvalidParamsException(Exception::UNIPAY_FIND_STRING_NOT_SUPPORTED);
        }

        Event::dispatch(new Event\MethodCalled('unipay', __METHOD__, $order, null));

        return $this->__call('query', [$order]);
    }

    /**
     * @param array|string $order
     * @return array|Collection|MessageInterface|null
     * @throws InvalidParamsException
     */
    public function cancel($order)
    {
        if (!is_array($order)) {
            throw new InvalidParamsException(Exception::UNIPAY_CANCEL_STRING_NOT_SUPPORTED);
        }

        Event::dispatch(new Event\MethodCalled('unipay', __METHOD__, $order, null));

        return $this->__call('cancel', [$order]);
    }

    /**
     * @param array|string $order
     *
     * @return void
     *
     * @throws InvalidParamsException
     */
    public function close($order)
    {
        throw new InvalidParamsException(Exception::METHOD_NOT_SUPPORTED, 'Unipay does not support close api');
    }

    /**
     * @param array $order
     * @return array|Collection|MessageInterface|null
     * @throws InvalidParamsException
     */
    public function refund(array $order)
    {
        Event::dispatch(new Event\MethodCalled('unipay', __METHOD__, $order, null));

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

        Event::dispatch(new Event\CallbackReceived('unipay', $request->all(), $params, null));

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
            return Collection::wrap($contents->getParsedBody());
        }

        $request = ServerRequest::fromGlobals();

        return Collection::wrap($request->getParsedBody());
    }
}
