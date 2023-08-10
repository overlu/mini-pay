<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Alipay\Shortcut;

use MiniPay\Contract\ShortcutInterface;
use MiniPay\Plugin\Alipay\Trade\PayPlugin;

/**
 * Class PosShortcut
 * @package MiniPay\Plugin\Alipay\Shortcut
 */
class PosShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            PayPlugin::class,
        ];
    }
}
