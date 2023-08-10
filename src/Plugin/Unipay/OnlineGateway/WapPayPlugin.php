<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Unipay\OnlineGateway;

use MiniPay\Direction\ResponseDirection;
use MiniPay\Plugin\Unipay\GeneralPlugin;
use MiniPay\Rocket;

/**
 * Class WapPayPlugin
 * @package MiniPay\Plugin\Unipay\OnlineGateway
 * @see https://open.unionpay.com/tjweb/acproduct/APIList?acpAPIId=754&apiservId=448&version=V2.2&bussType=0
 */
class WapPayPlugin extends GeneralPlugin
{
    protected function getUri(Rocket $rocket): string
    {
        return 'gateway/api/frontTransReq.do';
    }

    protected function doSomething(Rocket $rocket): void
    {
        $rocket->setDirection(ResponseDirection::class)
            ->mergePayload([
                'bizType' => '000201',
                'txnType' => '01',
                'txnSubType' => '01',
                'channelType' => '08',
            ])
        ;
    }
}
