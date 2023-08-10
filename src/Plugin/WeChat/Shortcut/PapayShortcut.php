<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Shortcut;

use Mini\Support\Str;
use MiniPay\Contract\ShortcutInterface;
use MiniPay\Exception\Exception;
use MiniPay\Exception\InvalidParamsException;
use MiniPay\Plugin\ParserPlugin;
use MiniPay\Plugin\WeChat\Papay\ApplyPlugin;
use MiniPay\Plugin\WeChat\Papay\ContractOrderPlugin;
use MiniPay\Plugin\WeChat\Papay\OnlyContractPlugin;
use MiniPay\Plugin\WeChat\Pay\Common\InvokePrepayV2Plugin;
use MiniPay\Plugin\WeChat\PreparePlugin;
use MiniPay\Plugin\WeChat\RadarSignPlugin;

/**
 * Class PapayShortcut
 * @package MiniPay\Plugin\WeChat\Shortcut
 */
class PapayShortcut implements ShortcutInterface
{
    /**
     * @throws InvalidParamsException
     */
    public function getPlugins(array $params): array
    {
        $typeMethod = Str::camel($params['_action'] ?? 'default') . 'Plugins';

        if (method_exists($this, $typeMethod)) {
            return $this->{$typeMethod}($params);
        }

        throw new InvalidParamsException(Exception::SHORTCUT_MULTI_ACTION_ERROR, "Papay action [{$typeMethod}] not supported");
    }

    /**
     * 返回只签约（委托代扣）参数.
     * @return string[]
     * @see https://pay.weixin.qq.com/wiki/doc/api/wxpay_v2/papay/chapter3_3.shtml
     */
    public function ContractPlugins(): array
    {
        return [
            PreparePlugin::class,
            OnlyContractPlugin::class,
        ];
    }

    /**
     * 申请代扣
     * @return string[]
     * @see https://pay.weixin.qq.com/wiki/doc/api/wxpay_v2/papay/chapter3_8.shtml
     */
    public function applyPlugins(): array
    {
        return [
            PreparePlugin::class,
            ApplyPlugin::class,
            RadarSignPlugin::class,
            ParserPlugin::class,
        ];
    }

    /**
     * 支付中签约
     * @param array $params
     * @return array
     * @see https://pay.weixin.qq.com/wiki/doc/api/wxpay_v2/papay/chapter3_5.shtml
     */
    protected function defaultPlugins(array $params): array
    {
        return [
            PreparePlugin::class,
            ContractOrderPlugin::class,
            RadarSignPlugin::class,
            $this->getInvoke($params),
            ParserPlugin::class,
        ];
    }

    /**
     * @param array $params
     * @return string
     */
    protected function getInvoke(array $params): string
    {
        switch ($params['_type'] ?? 'default') {
            case 'app':
                return \MiniPay\Plugin\WeChat\Pay\App\InvokePrepayV2Plugin::class;

            case 'mini':
                return \MiniPay\Plugin\WeChat\Pay\Mini\InvokePrepayV2Plugin::class;

            default:
                return InvokePrepayV2Plugin::class;
        }
    }
}
