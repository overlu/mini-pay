<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Pay\Common;

use Closure;
use Mini\Support\Collection;
use Mini\Support\Str;
use MiniPay\Contract\PluginInterface;
use MiniPay\Exception\Exception;
use MiniPay\Exception\InvalidConfigException;
use MiniPay\Exception\InvalidResponseException;
use MiniPay\Logger;
use MiniPay\Pay;
use MiniPay\Rocket;

use function MiniPay\get_wechat_config;
use function MiniPay\get_wechat_sign;

/**
 * Class InvokePrepayPlugin
 * @package MiniPay\Plugin\WeChat\Pay\Common
 */
class InvokePrepayPlugin implements PluginInterface
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

        Logger::debug('[wechat][InvokePrepayPlugin] 插件开始装载', ['rocket' => $rocket]);

        $prepayId = $rocket->getDestination()->get('prepay_id');

        if (is_null($prepayId)) {
            Logger::error('[wechat][InvokePrepayPlugin] 预下单失败：响应缺少 prepay_id 参数，请自行检查参数是否符合微信要求', $rocket->getDestination()->all());

            throw new InvalidResponseException(Exception::RESPONSE_MISSING_NECESSARY_PARAMS, 'Prepay Response Error: Missing PrepayId', $rocket->getDestination()->all());
        }

        $config = $this->getInvokeConfig($rocket, $prepayId);

        $rocket->setDestination($config);

        Logger::info('[wechat][InvokePrepayPlugin] 插件装载完毕', ['rocket' => $rocket]);

        return $rocket;
    }

    /**
     * @param Rocket $rocket
     * @param string $prepayId
     * @return Collection
     * @throws InvalidConfigException
     * @throws \Exception
     */
    protected function getInvokeConfig(Rocket $rocket, string $prepayId): Collection
    {
        $config = new Collection([
            'appId' => $this->getAppId($rocket),
            'timeStamp' => time() . '',
            'nonceStr' => Str::random(32),
            'package' => 'prepay_id=' . $prepayId,
            'signType' => 'RSA',
        ]);

        return $config->put('paySign', $this->getSign($config, $rocket->getParams()));
    }

    /**
     * @param Collection $invokeConfig
     * @param array $params
     * @return string
     * @throws InvalidConfigException
     */
    protected function getSign(Collection $invokeConfig, array $params): string
    {
        $contents = $invokeConfig->get('appId', '') . "\n" .
            $invokeConfig->get('timeStamp', '') . "\n" .
            $invokeConfig->get('nonceStr', '') . "\n" .
            $invokeConfig->get('package', '') . "\n";

        return get_wechat_sign($params, $contents);
    }

    /**
     * @param Rocket $rocket
     * @return string
     */
    protected function getAppId(Rocket $rocket): string
    {
        $config = get_wechat_config($rocket->getParams());
        $payload = $rocket->getPayload();

        if (Pay::MODE_SERVICE === ($config['mode'] ?? null) && $payload->has('sub_appid')) {
            return $payload->get('sub_appid', '');
        }

        return $config[$this->getConfigKey()] ?? '';
    }

    protected function getConfigKey(): string
    {
        return 'mp_app_id';
    }
}
