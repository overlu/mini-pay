<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Traits;

use Mini\Support\Str;

/**
 * Class Accessable
 * @package MiniPay\Traits
 */
trait Accessable
{
    /**
     * @param string $key
     * @return mixed
     */
    public function __get(string $key)
    {
        return $this->get($key);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function __isset(string $key): bool
    {
        return !is_null($this->get($key));
    }

    /**
     * @param string $key
     */
    public function __unset(string $key)
    {
        $this->offsetUnset($key);
    }

    /**
     * @param string $key
     * @param $value
     */
    public function __set(string $key, $value): void
    {
        $this->set($key, $value);
    }

    /**
     * @param string|null $key
     * @param null $default
     * @return mixed|null
     */
    public function get(?string $key = null, $default = null)
    {
        if (is_null($key)) {
            return method_exists($this, 'toArray') ? $this->toArray() : $default;
        }

        $method = 'get' . Str::studly($key);

        if (method_exists($this, $method)) {
            return $this->{$method}();
        }

        return $default;
    }

    /**
     * @param string $key
     * @param $value
     * @return $this
     */
    public function set(string $key, $value): self
    {
        $method = 'set' . Str::studly($key);

        if (method_exists($this, $method)) {
            $this->{$method}($value);
        }

        return $this;
    }

    /**
     * @param $offset
     * @return bool
     */
    public function offsetExists($offset): bool
    {
        return !is_null($this->get($offset));
    }

    /**
     * @param $offset
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @param $offset
     * @param $value
     */
    public function offsetSet($offset, $value): void
    {
        $this->set($offset, $value);
    }

    /**
     * @param $offset
     */
    public function offsetUnset($offset): void
    {
        $method = 'unset' . Str::studly($offset);

        if (method_exists($this, $method)) {
            $this->{$method}();
        }
    }
}
