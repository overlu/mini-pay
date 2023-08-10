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
 * Class ScanNormalPlugin
 * @package MiniPay\Plugin\Unipay\QrCode
 * @see https://open.unionpay.com/tjweb/acproduct/APIList?acpAPIId=793&apiservId=468&version=V2.2&bussType=0
 */
class ScanNormalPlugin extends GeneralPlugin
{
    protected function getUri(Rocket $rocket): string
    {
        return 'gateway/api/backTransReq.do';
    }

    protected function doSomething(Rocket $rocket): void
    {
        $rocket->mergePayload([
            'bizType' => '000000',
            'txnType' => '01',
            'txnSubType' => '07',
            'channelType' => '08',
        ]);
    }
}
