<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Marketing\Coupon;

use MiniPay\Exception\Exception;
use MiniPay\Exception\InvalidParamsException;
use MiniPay\Plugin\WeChat\GeneralPlugin;
use MiniPay\Rocket;

use function MiniPay\get_wechat_config;

/**
 * Class QueryStockItemsPlugin
 * @package MiniPay\Plugin\WeChat\Marketing\Coupon
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter9_1_8.shtml
 */
class QueryStockItemsPlugin extends GeneralPlugin
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
        $params = $rocket->getParams();
        $config = get_wechat_config($params);

        if (!$payload->has('stock_id')) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        if (!$payload->has('stock_creator_mchid')) {
            $rocket->mergePayload(['stock_creator_mchid' => $config['mch_id']]);
        }

        $query = $rocket->getPayload()->all();

        unset($query['stock_id']);

        return 'v3/marketing/favor/stocks/' .
            $payload->get('stock_id') .
            '/items?' . http_build_query($query);
    }
}
