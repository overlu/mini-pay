<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Provider;

use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Utils;
use Mini\Support\Collection;
use Mini\Support\Pipeline;
use MiniPay\Contract\HttpClientInterface;
use MiniPay\Exception\InvalidConfigException;
use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\RequestInterface;
use Swoole\Coroutine\Http\Client;
use Throwable;
use MiniPay\Contract\PluginInterface;
use MiniPay\Contract\ProviderInterface;
use MiniPay\Contract\ShortcutInterface;
use MiniPay\Direction\ArrayDirection;
use MiniPay\Event;
use MiniPay\Exception\Exception;
use MiniPay\Exception\InvalidParamsException;
use MiniPay\Exception\InvalidResponseException;
use MiniPay\Logger;
use MiniPay\Rocket;
use Psr\Http\Client\ClientInterface;

use function MiniPay\should_do_http_request;

/**
 * Class AbstractProvider
 * @package MiniPay\Provider
 */
abstract class AbstractProvider implements ProviderInterface
{
    /**
     * @param string $plugin
     * @param array $params
     * @return array|Collection|MessageInterface
     * @throws InvalidParamsException
     */
    public function call(string $plugin, array $params = [])
    {
        if (!class_exists($plugin) || !in_array(ShortcutInterface::class, class_implements($plugin), true)) {
            throw new InvalidParamsException(Exception::SHORTCUT_NOT_FOUND, "[{$plugin}] is not incompatible");
        }

        /* @var ShortcutInterface $money */
        $money = app($plugin);

        $plugins = $money->getPlugins($params);

        if (empty($params['_no_common_plugins'])) {
            $plugins = $this->mergeCommonPlugins($plugins);
        }

        return $this->pay($plugins, $params);
    }

    /**
     * @param array $plugins
     * @param array $params
     * @return array|Collection|MessageInterface|null
     * @throws InvalidParamsException
     */
    public function pay(array $plugins, array $params)
    {
        Logger::info('[AbstractProvider] 即将进行 pay 操作', func_get_args());

        Event::dispatch(new Event\PayStarted($plugins, $params, null));

        $this->verifyPlugin($plugins);

        /* @var Rocket $rocket */
        $rocket = (new Pipeline(app()))
            ->send((new Rocket())->setParams($params)->setPayload(new Collection()))
            ->through($plugins)
            ->via('assembly')
            ->then(fn($rocket) => $this->ignite($rocket));

        Event::dispatch(new Event\PayFinish($rocket));

        $destination = $rocket->getDestination();

        if ($destination instanceof Collection && ArrayDirection::class === $rocket->getDirection()) {
            return $destination->toArray();
        }

        return $destination;
    }

    /**
     * @param Rocket $rocket
     * @return Rocket
     * @throws InvalidResponseException
     */
    public function ignite(Rocket $rocket): Rocket
    {
        if (!should_do_http_request($rocket->getDirection())) {
            return $rocket;
        }

        Logger::info('[AbstractProvider] 准备请求支付服务商 API', $rocket->toArray());

        Event::dispatch(new Event\ApiRequesting($rocket));

        try {
            if (($app = app()) && $app->has(HttpClientInterface::class) && $client = $app->get(HttpClientInterface::class)) {
                if ($client instanceof ClientInterface) {
                    $response = $client->sendRequest($rocket->getRadar());
                } elseif ($client instanceof \GuzzleHttp\ClientInterface) {
                    $response = $client->send($rocket->getRadar());
                } else {
                    throw new InvalidConfigException(Exception::HTTP_CLIENT_CONFIG_ERROR);
                }
            } else {
                $response = $this->sendRequest($rocket->getRadar());
            }
            $body = Utils::streamFor($response->getBody());

            $rocket->setDestination($response->withBody($body))
                ->setDestinationOrigin($response->withBody($body));
        } catch (Throwable $e) {
            Logger::error('[AbstractProvider] 请求支付服务商 API 出错', ['message' => $e->getMessage(), 'rocket' => $rocket->toArray(), 'trace' => $e->getTrace()]);

            throw new InvalidResponseException(Exception::REQUEST_RESPONSE_ERROR, $e->getMessage(), [], $e);
        }

        Logger::info('[AbstractProvider] 请求支付服务商 API 成功', ['response' => $response, 'rocket' => $rocket->toArray()]);

        Event::dispatch(new Event\ApiRequested($rocket));

        return $rocket;
    }

    protected function sendRequest(RequestInterface $request): Response
    {
        $uri = $request->getUri();
        $isSSL = strtolower($uri->getScheme()) === 'https';
        $client = new Client($uri->getHost(), $isSSL ? 443 : 80, $isSSL);
        $httpConfig = config('pay.http');
        if (!empty($httpConfig) && is_array($httpConfig)) {
            $client->set($httpConfig);
        }
        if ($request->getBody()->getSize()) {
            $client->setData((string)$request->getBody());
        }
        $headers = [];
        foreach ($request->getHeaders() as $key => $val) {
            $headers[$key] = implode(',', $val);
        }
        if (!empty($headers)) {
            $client->setHeaders($headers);
        }

        if ($request->getBody()->getSize()) {
            $client->setData((string)$request->getBody());
        }
        $client->setMethod($request->getMethod());
        $client->execute($uri->getPath());
        $responseBody = $client->getBody() ?: null;
        $responseStatusCode = (int)$client->getStatusCode();
        $responseHeaders = (array)$client->getHeaders();
        $client->close();
        return new Response($responseStatusCode, $responseHeaders, $responseBody);
    }

    abstract public function mergeCommonPlugins(array $plugins): array;

    /**
     * @throws InvalidParamsException
     */
    protected function verifyPlugin(array $plugins): void
    {
        foreach ($plugins as $plugin) {
            if (is_callable($plugin)) {
                continue;
            }

            if ((is_object($plugin)
                    || (is_string($plugin) && class_exists($plugin)))
                && in_array(PluginInterface::class, class_implements($plugin), true)) {
                continue;
            }

            throw new InvalidParamsException(Exception::PLUGIN_ERROR, "[{$plugin}] is not incompatible");
        }
    }
}
