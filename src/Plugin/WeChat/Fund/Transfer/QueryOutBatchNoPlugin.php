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
 * Class QueryOutBatchNoPlugin
 * @package MiniPay\Plugin\WeChat\Fund\Transfer
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter4_3_5.shtml
 */
class QueryOutBatchNoPlugin extends GeneralPlugin
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

        if (!$payload->has('out_batch_no') || !$payload->has('need_query_detail')) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        $outBatchNo = $payload->get('out_batch_no');

        $payload->forget('out_batch_no');

        return 'v3/transfer/batches/out-batch-no/' . $outBatchNo .
            '?' . $payload->query();
    }

    /**
     * @param Rocket $rocket
     * @return string
     * @throws InvalidParamsException
     */
    protected function getPartnerUri(Rocket $rocket): string
    {
        $payload = $rocket->getPayload();

        if (!$payload->has('out_batch_no') || !$payload->has('need_query_detail')) {
            throw new InvalidParamsException(Exception::MISSING_NECESSARY_PARAMS);
        }

        $outBatchNo = $payload->get('out_batch_no');

        $payload->forget('out_batch_no');

        return 'v3/partner-transfer/batches/out-batch-no/' . $outBatchNo .
            '?' . $payload->query();
    }
}
