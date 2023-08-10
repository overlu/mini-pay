<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Packer;

use MiniPay\Contract\PackerInterface;

/**
 * Class JsonPacker
 * @package MiniPay\Packer
 */
class JsonPacker implements PackerInterface
{
    public function pack(array $payload): string
    {
        return json_encode($payload, JSON_UNESCAPED_UNICODE);
    }

    public function unpack(string $payload): ?array
    {
        $data = json_decode($payload, true);

        if (JSON_ERROR_NONE === json_last_error()) {
            return $data;
        }

        return null;
    }
}
