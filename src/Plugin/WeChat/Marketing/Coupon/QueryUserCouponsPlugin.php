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
 * Class QueryUserCouponsPlugin
 * @package MiniPay\Plugin\WeChat\Marketing\Coupon
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter9_1_9.shtml
 */
class QueryUserCouponsPlugin extends GeneralPlugin
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

        if (!$payload->has('openid')) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        if (!$payload->has('appid')) {
            $rocket->mergePayload(['appid' => $config[$this->getConfigKey($params)] ?? '']);
        }

        if (!$payload->has('creator_mchid')) {
            $rocket->mergePayload(['creator_mchid' => $config['mch_id']]);
        }

        $query = $rocket->getPayload()->all();

        unset($query['openid']);

        return 'v3/marketing/favor/users/' .
            $payload->get('openid') .
            '/coupons?' . http_build_query($query);
    }
}
