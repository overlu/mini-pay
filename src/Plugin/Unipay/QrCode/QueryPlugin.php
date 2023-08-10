<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Unipay\QrCode;

use MiniPay\Pay;
use MiniPay\Rocket;

use function MiniPay\get_unipay_config;

/**
 * Class QueryPlugin
 * @package MiniPay\Plugin\Unipay\QrCode
 * @see https://open.unionpay.com/tjweb/acproduct/APIList?acpAPIId=792&apiservId=468&version=V2.2&bussType=0
 */
class QueryPlugin extends \MiniPay\Plugin\Unipay\OnlineGateway\QueryPlugin
{
    /**
     * @param Rocket $rocket
     * @return string
     */
    protected function getUri(Rocket $rocket): string
    {
        $config = get_unipay_config($rocket->getParams());

        if (Pay::MODE_SANDBOX === ($config['mode'] ?? Pay::MODE_NORMAL)) {
            return 'https://101.231.204.80:5000/gateway/api/backTransReq.do';
        }

        return parent::getUri($rocket);
    }
}
