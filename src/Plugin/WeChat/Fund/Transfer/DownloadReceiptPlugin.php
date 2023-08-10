<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Fund\Transfer;

use MiniPay\Direction\OriginResponseDirection;
use MiniPay\Exception\Exception;
use MiniPay\Exception\InvalidParamsException;
use MiniPay\Plugin\WeChat\GeneralPlugin;
use MiniPay\Rocket;

/**
 * Class DownloadReceiptPlugin
 * @package MiniPay\Plugin\WeChat\Fund\Transfer
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter4_3_11.shtml
 */
class DownloadReceiptPlugin extends GeneralPlugin
{
    /**
     * @param Rocket $rocket
     * @return string
     * @throws InvalidParamsException
     */
    protected function getUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();

        if (!$payload->has('download_url')) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        return $payload->get('download_url');
    }

    protected function getMethod(): string
    {
        return 'GET';
    }

    protected function doSomething(Rocket $rocket): void
    {
        $rocket->setDirection(OriginResponseDirection::class);

        $rocket->setPayload(null);
    }
}
