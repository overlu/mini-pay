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
use MiniPay\Plugin\WeChat\Pay\Common\QueryPlugin;
use MiniPay\Plugin\WeChat\Pay\Common\QueryRefundPlugin;

/**
 * Class QueryShortcut
 * @package MiniPay\Plugin\WeChat\Shortcut
 */
class QueryShortcut implements ShortcutInterface
{
    /**
     * @throws InvalidParamsException
     */
    public function getPlugins(array $params): array
    {
        if (isset($params['combine_out_trade_no'])) {
            return $this->combinePlugins();
        }

        $typeMethod = Str::camel($params['_action'] ?? 'default') . 'Plugins';

        if (method_exists($this, $typeMethod)) {
            return $this->{$typeMethod}();
        }

        throw new InvalidParamsException(Exception::SHORTCUT_MULTI_ACTION_ERROR, "Query action [{$typeMethod}] not supported");
    }

    protected function defaultPlugins(): array
    {
        return [
            QueryPlugin::class,
        ];
    }

    protected function refundPlugins(): array
    {
        return [
            QueryRefundPlugin::class,
        ];
    }

    protected function combinePlugins(): array
    {
        return [
            \MiniPay\Plugin\WeChat\Pay\Combine\QueryPlugin::class,
        ];
    }
}
