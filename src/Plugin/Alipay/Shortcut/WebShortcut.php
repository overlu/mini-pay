<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Alipay\Shortcut;

use MiniPay\Contract\ShortcutInterface;
use MiniPay\Plugin\Alipay\HtmlResponsePlugin;
use MiniPay\Plugin\Alipay\Trade\PagePayPlugin;

/**
 * Class WebShortcut
 * @package MiniPay\Plugin\Alipay\Shortcut
 */
class WebShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            PagePayPlugin::class,
            HtmlResponsePlugin::class,
        ];
    }
}
