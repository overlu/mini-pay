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
use MiniPay\Plugin\Unipay\OnlineGateway\CancelPlugin;

/**
 * Class CancelShortcut
 * @package MiniPay\Plugin\Unipay\Shortcut
 */
class CancelShortcut implements ShortcutInterface
{
    /**
     * @throws InvalidParamsException
     */
    public function getPlugins(array $params): array
    {
        $typeMethod = Str::camel($params['_action'] ?? 'default') . 'Plugins';

        if (method_exists($this, $typeMethod)) {
            return $this->{$typeMethod}();
        }

        throw new InvalidParamsException(Exception::SHORTCUT_MULTI_ACTION_ERROR, "Cancel action [{$typeMethod}] not supported");
    }

    protected function defaultPlugins(): array
    {
        return [
            CancelPlugin::class,
        ];
    }

    protected function qrCodePlugins(): array
    {
        return [
            \MiniPay\Plugin\Unipay\QrCode\CancelPlugin::class,
        ];
    }
}
