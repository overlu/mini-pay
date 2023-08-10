<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Unipay\QrCode;

use MiniPay\Plugin\Unipay\GeneralPlugin;
use MiniPay\Rocket;

/**
 * Class ScanPreOrderPlugin
 * @package MiniPay\Plugin\Unipay\QrCode
 * @see https://open.unionpay.com/tjweb/acproduct/APIList?acpAPIId=795&apiservId=468&version=V2.2&bussType=0
 */
class ScanPreOrderPlugin extends GeneralPlugin
{
    protected function getUri(Rocket $rocket): string
    {
        return 'gateway/api/order.do';
    }

    protected function doSomething(Rocket $rocket): void
    {
        $rocket->mergePayload([
            'bizType' => '000000',
            'txnType' => '01',
            'txnSubType' => '01',
            'channelType' => '08',
        ]);
    }
}
