<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Risk\Complaints;

use Mini\Support\Collection;
use MiniPay\Direction\OriginResponseDirection;
use MiniPay\Exception\Exception;
use MiniPay\Exception\InvalidParamsException;
use MiniPay\Plugin\WeChat\GeneralPlugin;
use MiniPay\Rocket;

use function MiniPay\get_wechat_config;

/**
 * Class CompleteComplaintPlugin
 * @package MiniPay\Plugin\WeChat\Risk\Complaints
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter10_2_15.shtml
 */
class CompleteComplaintPlugin extends GeneralPlugin
{
    /**
     * @param Rocket $rocket
     */
    protected function doSomething(Rocket $rocket): void
    {
        $rocket->setDirection(OriginResponseDirection::class);

        $payload = $rocket->getPayload();
        $config = get_wechat_config($rocket->getParams());

        $rocket->setPayload(new Collection([
            'complainted_mchid' => $payload->get('complainted_mchid', $config['mch_id'] ?? ''),
        ]));
    }

    /**
     * @throws InvalidParamsException
     */
    protected function getUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();

        if (!$payload->has('complaint_id')) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        return 'v3/merchant-service/complaints-v2/' .
            $payload->get('complaint_id') .
            '/complete';
    }
}
