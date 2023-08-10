<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Unipay\Shortcut;

use MiniPay\Contract\ShortcutInterface;
use MiniPay\Plugin\Unipay\HtmlResponsePlugin;
use MiniPay\Plugin\Unipay\OnlineGateway\PagePayPlugin;

/**
 * Class WebShortcut
 * @package MiniPay\Plugin\Unipay\Shortcut
 */
class WebShortcut implements ShortcutInterface
{
    /**
     * @param array $params
     * @return string[]
     */
    public function getPlugins(array $params): array
    {
        return [
            PagePayPlugin::class,
            HtmlResponsePlugin::class,
        ];
    }
}
