<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay;

use JsonSerializable as JsonSerializableInterface;
use MiniPay\Traits\Accessable;
use MiniPay\Traits\Arrayable;
use MiniPay\Traits\Serializable;

/**
 * Class Request
 * @package MiniPay
 */
class Request extends \GuzzleHttp\Psr7\Request implements JsonSerializableInterface
{
    use Accessable;
    use Arrayable;
    use Serializable;

    public function toArray(): array
    {
        return [
            'url' => (string)$this->getUri(),
            'method' => $this->getMethod(),
            'headers' => $this->getHeaders(),
            'body' => (string)$this->getBody(),
        ];
    }
}
