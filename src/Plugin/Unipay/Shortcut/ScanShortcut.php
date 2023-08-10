<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Unipay\Shortcut;

use Mini\Support\Str;
use MiniPay\Contract\ShortcutInterface;
use MiniPay\Exception\Exception;
use MiniPay\Exception\InvalidParamsException;
use MiniPay\Plugin\Unipay\QrCode\ScanFeePlugin;
use MiniPay\Plugin\Unipay\QrCode\ScanNormalPlugin;
use MiniPay\Plugin\Unipay\QrCode\ScanPreAuthPlugin;
use MiniPay\Plugin\Unipay\QrCode\ScanPreOrderPlugin;

/**
 * Class ScanShortcut
 * @package MiniPay\Plugin\Unipay\Shortcut
 */
class ScanShortcut implements ShortcutInterface
{
    /**
     * @param array $params
     * @return array
     * @throws InvalidParamsException
     */
    public function getPlugins(array $params): array
    {
        $typeMethod = Str::camel($params['_action'] ?? 'default') . 'Plugins';

        if (method_exists($this, $typeMethod)) {
            return $this->{$typeMethod}();
        }

        throw new InvalidParamsException(Exception::SHORTCUT_MULTI_ACTION_ERROR, "Scan action [{$typeMethod}] not supported");
    }

    protected function defaultPlugins(): array
    {
        return [
            ScanNormalPlugin::class,
        ];
    }

    protected function preAuthPlugins(): array
    {
        return [
            ScanPreAuthPlugin::class,
        ];
    }

    protected function preOrderPlugins(): array
    {
        return [
            ScanPreOrderPlugin::class,
        ];
    }

    protected function feePlugins(): array
    {
        return [
            ScanFeePlugin::class,
        ];
    }
}
