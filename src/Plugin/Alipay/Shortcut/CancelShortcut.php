<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Alipay\Shortcut;

use MiniPay\Contract\ShortcutInterface;
use MiniPay\Plugin\Alipay\Trade\CancelPlugin;

/**
 * Class CancelShortcut
 * @package MiniPay\Plugin\Alipay\Shortcut
 */
class CancelShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            CancelPlugin::class,
        ];
    }
}
