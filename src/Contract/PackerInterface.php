<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Contract;

/**
 * Class PackerInterface
 * @package MiniPay\Contract
 */
interface PackerInterface
{
    public function pack(array $payload): string;

    public function unpack(string $payload): ?array;
}
