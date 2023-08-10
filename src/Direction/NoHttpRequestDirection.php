<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Direction;

use Psr\Http\Message\ResponseInterface;
use MiniPay\Contract\DirectionInterface;
use MiniPay\Contract\PackerInterface;

/**
 * Class NoHttpRequestDirection
 * @package MiniPay\Direction
 */
class NoHttpRequestDirection implements DirectionInterface
{
    public function parse(PackerInterface $packer, ?ResponseInterface $response): ?ResponseInterface
    {
        return $response;
    }
}
