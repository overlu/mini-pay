<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Contract;

use Psr\Http\Message\ResponseInterface;

/**
 * Class DirectionInterface
 * @package MiniPay\Contract
 */
interface DirectionInterface
{
    /**
     * @return mixed
     */
    public function parse(PackerInterface $packer, ?ResponseInterface $response);
}
