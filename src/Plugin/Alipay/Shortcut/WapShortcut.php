<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Alipay\Shortcut;

use MiniPay\Contract\ShortcutInterface;
use MiniPay\Plugin\Alipay\HtmlResponsePlugin;
use MiniPay\Plugin\Alipay\Trade\WapPayPlugin;

/**
 * Class WapShortcut
 * @package MiniPay\Plugin\Alipay\Shortcut
 */
class WapShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            WapPayPlugin::class,
            HtmlResponsePlugin::class,
        ];
    }
}
