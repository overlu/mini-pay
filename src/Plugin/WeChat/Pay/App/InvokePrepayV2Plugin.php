<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Pay\App;

use Exception;
use Mini\Support\Collection;
use Mini\Support\Str;
use MiniPay\Exception\InvalidConfigException;
use MiniPay\Rocket;

use function MiniPay\get_wechat_config;
use function MiniPay\get_wechat_sign_v2;

/**
 * Class InvokePrepayV2Plugin
 * @package MiniPay\Plugin\WeChat\Pay\App
 * @see https://pay.weixin.qq.com/wiki/doc/api/app/app.php?chapter=8_5
 */
class InvokePrepayV2Plugin extends \MiniPay\Plugin\WeChat\Pay\Common\InvokePrepayPlugin
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
        $params = $rocket->getParams();

        $config = new Collection([
            'appId' => $this->getAppId($rocket),
            'partnerId' => get_wechat_config($params)['mch_id'] ?? null,
            'prepayId' => $prepayId,
            'package' => 'Sign=WXPay',
            'nonceStr' => Str::random(32),
            'timeStamp' => time() . '',
        ]);

        $config->put('sign', get_wechat_sign_v2($params, $config->all()));

        return $config;
    }

    protected function getConfigKey(): string
    {
        return 'app_id';
    }
}
