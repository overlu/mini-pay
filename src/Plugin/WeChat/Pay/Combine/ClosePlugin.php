<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Pay\Combine;

use Mini\Support\Collection;
use MiniPay\Exception\Exception;
use MiniPay\Exception\InvalidParamsException;
use MiniPay\Rocket;

use function MiniPay\get_wechat_config;

/**
 * Class ClosePlugin
 * @package MiniPay\Plugin\WeChat\Pay\Combine
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter5_1_12.shtml
 */
class ClosePlugin extends \MiniPay\Plugin\WeChat\Pay\Common\ClosePlugin
{
    protected function getUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();

        if (!$payload->has('combine_out_trade_no') && !$payload->has('out_trade_no')) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        return 'v3/combine-transactions/out-trade-no/' .
            $payload->get('combine_out_trade_no', $payload->get('out_trade_no')) .
            '/close';
    }

    /**
     * @param Rocket $rocket
     */
    protected function doSomething(Rocket $rocket): void
    {
        $config = get_wechat_config($rocket->getParams());

        $rocket->setPayload(new Collection([
            'combine_appid' => $config['combine_appid'] ?? '',
            'sub_orders' => $rocket->getParams()['sub_orders'] ?? [],
        ]));
    }
}
