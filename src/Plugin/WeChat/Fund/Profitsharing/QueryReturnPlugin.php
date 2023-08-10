<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Fund\Profitsharing;

use MiniPay\Exception\Exception;
use MiniPay\Exception\InvalidParamsException;
use MiniPay\Pay;
use MiniPay\Plugin\WeChat\GeneralPlugin;
use MiniPay\Rocket;

use function MiniPay\get_wechat_config;

/**
 * Class QueryReturnPlugin
 * @package MiniPay\Plugin\WeChat\Fund\Profitsharing
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter8_1_4.shtml
 */
class QueryReturnPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'GET';
    }

    protected function doSomething(Rocket $rocket): void
    {
        $rocket->setPayload(null);
    }

    /**
     * @param Rocket $rocket
     * @return string
     * @throws InvalidParamsException
     */
    protected function getUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();
        $config = get_wechat_config($rocket->getParams());

        if (!$payload->has('out_return_no') || !$payload->has('out_order_no')) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        $url = 'v3/profitsharing/return-orders/' .
            $payload->get('out_return_no') .
            '?out_order_no=' . $payload->get('out_order_no');

        if (Pay::MODE_SERVICE === ($config['mode'] ?? null)) {
            $url .= '&sub_mchid=' . $payload->get('sub_mchid', $config['sub_mch_id'] ?? '');
        }

        return $url;
    }
}
