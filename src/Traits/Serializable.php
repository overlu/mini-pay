<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Traits;

/**
 * Class Serializable
 * @package MiniPay\Traits
 */
trait Serializable
{
    public function __serialize(): array
    {
        if (method_exists($this, 'toArray')) {
            return $this->toArray();
        }

        return [];
    }

    public function __unserialize(array $data): void
    {
        $this->unserializeArray($data);
    }

    public function __toString(): string
    {
        return $this->toJson();
    }

    public function serialize(): string
    {
        return serialize($this);
    }

    public function unserialize($data): void
    {
        unserialize($data);
    }

    public function toJson(int $option = JSON_UNESCAPED_UNICODE): string
    {
        return json_encode($this->__serialize(), $option);
    }

    public function jsonSerialize(): array
    {
        return $this->__serialize();
    }

    public function unserializeArray(array $data): self
    {
        foreach ($data as $key => $item) {
            if (method_exists($this, 'set')) {
                $this->set($key, $item);
            }
        }

        return $this;
    }
}
