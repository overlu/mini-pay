<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Alipay\Shortcut;

use MiniPay\Contract\ShortcutInterface;
use MiniPay\Plugin\Alipay\Trade\PreCreatePlugin;

/**
 * Class ScanShortcut
 * @package MiniPay\Plugin\Alipay\Shortcut
 */
class ScanShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            PreCreatePlugin::class,
        ];
    }
}
