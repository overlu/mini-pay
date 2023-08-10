<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin\Alipay\Shortcut;

use MiniPay\Contract\ShortcutInterface;
use MiniPay\Plugin\Alipay\Fund\TransUniTransferPlugin;

/**
 * Class TransferShortcut
 * @package MiniPay\Plugin\Alipay\Shortcut
 */
class TransferShortcut implements ShortcutInterface
{
    public function getPlugins(array $params): array
    {
        return [
            TransUniTransferPlugin::class,
        ];
    }
}
