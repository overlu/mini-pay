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
 * Class ArrayDirection
 * @package MiniPay\Direction
 */
class ArrayDirection implements DirectionInterface
{
    /**
     * @throws InvalidResponseException
     */
    public function parse(PackerInterface $packer, ?ResponseInterface $response): array
    {
        if (is_null($response)) {
            throw new InvalidResponseException(Exception::RESPONSE_NONE);
        }
        $body = (string)$response->getBody();
        if (!is_null($result = $packer->unpack($body))) {
            return $result;
        }

        throw new InvalidResponseException(Exception::UNPACK_RESPONSE_ERROR, 'Unpack Response Error', ['body' => $body, 'response' => $response]);
    }
}
