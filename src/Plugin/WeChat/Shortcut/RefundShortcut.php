<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Shortcut;

use MiniPay\Contract\ShortcutInterface;
use MiniPay\Plugin\WeChat\Pay\Common\RefundPlugin;

/**
 * Class RefundShortcut
 * @package MiniPay\Plugin\WeChat\Shortcut
 */
class RefundShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            RefundPlugin::class,
        ];
    }
}
