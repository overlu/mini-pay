<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\WeChat\Shortcut;

use MiniPay\Contract\ShortcutInterface;
use MiniPay\Plugin\WeChat\Pay\Jsapi\InvokePrepayPlugin;
use MiniPay\Plugin\WeChat\Pay\Jsapi\PrepayPlugin;

/**
 * Class MpShortcut
 * @package MiniPay\Plugin\WeChat\Shortcut
 */
class MpShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            PrepayPlugin::class,
            InvokePrepayPlugin::class,
        ];
    }
}
