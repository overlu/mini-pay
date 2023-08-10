<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Unipay\OnlineGateway;

use MiniPay\Plugin\Unipay\GeneralPlugin;
use MiniPay\Rocket;

/**
 * Class RefundPlugin
 * @package MiniPay\Plugin\Unipay\OnlineGateway
 * @see https://open.unionpay.com/tjweb/acproduct/APIList?acpAPIId=756&apiservId=448&version=V2.2&bussType=0
 */
class RefundPlugin extends GeneralPlugin
{
    protected function getUri(Rocket $rocket): string
    {
        return 'gateway/api/backTransReq.do';
    }

    protected function doSomething(Rocket $rocket): void
    {
        $rocket->mergePayload([
            'bizType' => '000000',
            'txnType' => '04',
            'txnSubType' => '00',
            'channelType' => '07',
        ]);
    }
}
