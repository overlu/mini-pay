<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Shortcut;

use MiniPay\Contract\ShortcutInterface;
use MiniPay\Plugin\WeChat\Pay\App\InvokePrepayPlugin;
use MiniPay\Plugin\WeChat\Pay\App\PrepayPlugin;

/**
 * Class AppShortcut
 * @package MiniPay\Plugin\WeChat\Shortcut
 */
class AppShortcut implements ShortcutInterface
{
    /**
     * @param array $params
     * @return string[]
     */
    public function getPlugins(array $params): array
    {
        return [
            PrepayPlugin::class,
            InvokePrepayPlugin::class,
        ];
    }
}
