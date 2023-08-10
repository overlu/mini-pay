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
use MiniPay\Exception\Exception;
use MiniPay\Exception\InvalidResponseException;

/**
 * Class OriginResponseDirection
 * @package MiniPay\Direction
 */
class OriginResponseDirection implements DirectionInterface
{
    /**
     * @throws InvalidResponseException
     */
    public function parse(PackerInterface $packer, ?ResponseInterface $response): ?ResponseInterface
    {
        if (!is_null($response)) {
            return $response;
        }

        throw new InvalidResponseException(Exception::INVALID_RESPONSE_CODE);
    }
}
