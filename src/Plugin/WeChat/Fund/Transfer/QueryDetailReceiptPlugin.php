<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Fund\Transfer;

use MiniPay\Exception\Exception;
use MiniPay\Exception\InvalidParamsException;
use MiniPay\Plugin\WeChat\GeneralPlugin;
use MiniPay\Rocket;

/**
 * Class QueryDetailReceiptPlugin
 * @package MiniPay\Plugin\WeChat\Fund\Transfer
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter4_3_10.shtml
 */
class QueryDetailReceiptPlugin extends GeneralPlugin
{
    protected function getMethod(): string
    {
        return 'GET';
    }

    /**
     * @param Rocket $rocket
     * @throws InvalidParamsException
     */
    protected function doSomething(Rocket $rocket): void
    {
        $payload = $rocket->getPayload();

        if (!$payload->has('out_detail_no') || !$payload->has('accept_type')) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        $rocket->setPayload(null);
    }

    protected function getUri(Rocket $rocket): string
    {
        return 'v3/transfer-detail/electronic-receipts?' . $rocket->getPayload()->query();
    }
}
