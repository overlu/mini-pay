<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Contract;

/**
 * Class ShortcutInterface
 * @package MiniPay\Contract
 */
interface ShortcutInterface
{
    /**
     * @return PluginInterface[]|string[]
     */
    public function getPlugins(array $params): array;
}
