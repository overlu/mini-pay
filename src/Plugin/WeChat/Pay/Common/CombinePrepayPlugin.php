<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Pay\Common;

use MiniPay\Plugin\WeChat\GeneralPlugin;
use MiniPay\Rocket;

use function MiniPay\get_wechat_config;

/**
 * Class CombinePrepayPlugin
 * @package MiniPay\Plugin\WeChat\Pay\Common
 */
class CombinePrepayPlugin extends GeneralPlugin
{
    protected function getUri(Rocket $rocket): string
    {
        return 'v3/combine-transactions/jsapi';
    }

    /**
     * @param Rocket $rocket
     */
    protected function doSomething(Rocket $rocket): void
    {
        $config = get_wechat_config($rocket->getParams());
        $collection = $rocket->getPayload();

        $payload = $this->getWechatId($config);

        if (!$collection->has('notify_url')) {
            $payload['notify_url'] = $config['notify_url'] ?? '';
        }

        if (!$collection->has('combine_out_trade_no')) {
            $payload['combine_out_trade_no'] = $rocket->getParams()['out_trade_no'];
        }

        $rocket->mergePayload($payload);
    }

    protected function getWechatId(array $config): array
    {
        return [
            'combine_appid' => $config['combine_app_id'] ?? '',
            'combine_mchid' => $config['combine_mch_id'] ?? '',
        ];
    }
}
