<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Unipay\Shortcut;

use MiniPay\Contract\ShortcutInterface;
use MiniPay\Plugin\Unipay\HtmlResponsePlugin;
use MiniPay\Plugin\Unipay\OnlineGateway\WapPayPlugin;

/**
 * Class WapShortcut
 * @package MiniPay\Plugin\Unipay\Shortcut
 */
class WapShortcut implements ShortcutInterface
{
    /**
     * @param array $params
     * @return string[]
     */
    public function getPlugins(array $params): array
    {
        return [
            WapPayPlugin::class,
            HtmlResponsePlugin::class,
        ];
    }
}
