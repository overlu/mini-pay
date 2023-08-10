<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Direction;

use Mini\Support\Collection;
use Psr\Http\Message\ResponseInterface;
use MiniPay\Contract\DirectionInterface;
use MiniPay\Contract\PackerInterface;

/**
 * Class CollectionDirection
 * @package MiniPay\Direction
 */
class CollectionDirection implements DirectionInterface
{
    /**
     * @param PackerInterface $packer
     * @param ResponseInterface|null $response
     * @return Collection
     */
    public function parse(PackerInterface $packer, ?ResponseInterface $response): Collection
    {
        return new Collection(
            app(ArrayDirection::class)->parse($packer, $response)
        );
    }
}
