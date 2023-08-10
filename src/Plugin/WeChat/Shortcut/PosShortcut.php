<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Shortcut;

use MiniPay\Contract\ShortcutInterface;
use MiniPay\Plugin\WeChat\Pay\Pos\PayPlugin;

/**
 * Class PosShortcut
 * @package MiniPay\Plugin\WeChat\Shortcut
 */
class PosShortcut implements ShortcutInterface
{
    /**
     * @param array $params
     * @return string[]
     */
    public function getPlugins(array $params): array
    {
        return [
            PayPlugin::class,
        ];
    }
}
