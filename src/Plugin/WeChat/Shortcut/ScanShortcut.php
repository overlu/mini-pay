<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Shortcut;

use MiniPay\Contract\ShortcutInterface;
use MiniPay\Plugin\WeChat\Pay\Native\PrepayPlugin;

/**
 * Class ScanShortcut
 * @package MiniPay\Plugin\WeChat\Shortcut
 */
class ScanShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            PrepayPlugin::class,
        ];
    }
}
