<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Pay\Common;

use Exception;
use Mini\Support\Collection;
use Mini\Support\Str;
use MiniPay\Exception\InvalidConfigException;
use MiniPay\Rocket;

use function MiniPay\get_wechat_sign_v2;

/**
 * Class InvokePrepayV2Plugin
 * @package MiniPay\Plugin\WeChat\Pay\Common
 * @see https://pay.weixin.qq.com/wiki/doc/api/jsapi.php?chapter=7_7&index=6
 */
class InvokePrepayV2Plugin extends InvokePrepayPlugin
{
    /**
     * @param Rocket $rocket
     * @param string $prepayId
     * @return Collection
     * @throws InvalidConfigException
     * @throws Exception
     */
    protected function getInvokeConfig(Rocket $rocket, string $prepayId): Collection
    {
        $config = new Collection([
            'appId' => $this->getAppId($rocket),
            'timeStamp' => time() . '',
            'nonceStr' => Str::random(32),
            'package' => 'prepay_id=' . $prepayId,
            'signType' => 'MD5',
        ]);

        $config->put('paySign', get_wechat_sign_v2($rocket->getParams(), $config->toArray()));

        return $config;
    }
}
