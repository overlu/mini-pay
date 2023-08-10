<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat;

use MiniPay\Packer\XmlPacker;
use MiniPay\Rocket;

use function MiniPay\get_wechat_config;

/**
 * Class GeneralV2Plugin
 * @package MiniPay\Plugin\WeChat
 */
abstract class GeneralV2Plugin extends GeneralPlugin
{
    protected function getHeaders(): array
    {
        return [
            'Content-Type' => 'application/xml',
            'User-Agent' => 'mini-pay/pay-v3',
        ];
    }

    /**
     * @param Rocket $rocket
     */
    protected function doSomething(Rocket $rocket): void
    {
        $config = get_wechat_config($rocket->getParams());
        $configKey = $this->getConfigKey($rocket->getParams());

        $rocket->setPacker(XmlPacker::class)->mergeParams(['_version' => 'v2']);

        $rocket->mergePayload([
            'appid' => $config[$configKey] ?? '',
            'mch_id' => $config['mch_id'] ?? '',
        ]);
    }

    abstract protected function getUri(Rocket $rocket): string;
}
