<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Contract;

use Mini\Support\Collection;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class ProviderInterface
 * @package MiniPay\Contract
 */
interface ProviderInterface
{
    /**
     * @param array $plugins
     * @param array $params
     * @return mixed
     */
    public function pay(array $plugins, array $params);

    /**
     * @param array|string $order
     *
     * @return array|Collection
     */
    public function find($order);

    /**
     * @param array|string $order
     *
     * @return array|Collection|void
     */
    public function cancel($order);

    /**
     * @param array|string $order
     *
     * @return array|Collection|void
     */
    public function close($order);

    /**
     * @return array|Collection
     */
    public function refund(array $order);

    /**
     * @param null|array|ServerRequestInterface $contents
     */
    public function callback($contents = null, ?array $params = null): Collection;

    public function success(): ResponseInterface;
}
