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
use function MiniPay\get_wechat_sign;

/**
 * Class InvokePrepayPlugin
 * @package MiniPay\Plugin\WeChat\Pay\App
 * @see https://pay.weixin.qq.com/wiki/doc/apiv3/apis/chapter3_2_4.shtml
 */
class InvokePrepayPlugin extends \MiniPay\Plugin\WeChat\Pay\Common\InvokePrepayPlugin
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
            'appid' => $this->getAppId($rocket),
            'partnerid' => get_wechat_config($rocket->getParams())['mch_id'] ?? null,
            'prepayid' => $prepayId,
            'package' => 'Sign=WXPay',
            'noncestr' => Str::random(32),
            'timestamp' => time() . '',
        ]);

        return $config->put('sign', $this->getSign($config, $rocket->getParams()));
    }

    /**
     * @param Collection $invokeConfig
     * @param array $params
     * @return string
     * @throws InvalidConfigException
     */
    protected function getSign(Collection $invokeConfig, array $params): string
    {
        $contents = $invokeConfig->get('appid', '') . "\n" .
            $invokeConfig->get('timestamp', '') . "\n" .
            $invokeConfig->get('noncestr', '') . "\n" .
            $invokeConfig->get('prepayid', '') . "\n";

        return get_wechat_sign($params, $contents);
    }

    protected function getConfigKey(): string
    {
        return 'app_id';
    }
}
