<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay;

use ArrayAccess;
use JsonSerializable as JsonSerializableInterface;
use Mini\Support\Collection;
use MiniPay\Traits\Accessable;
use MiniPay\Traits\Arrayable;
use MiniPay\Traits\Serializable;
use Psr\Http\Message\MessageInterface;
use MiniPay\Contract\DirectionInterface;
use MiniPay\Contract\PackerInterface;
use Psr\Http\Message\RequestInterface;

/**
 * Class Rocket
 * @package MiniPay
 */
class Rocket implements JsonSerializableInterface, ArrayAccess
{
    use Accessable;
    use Arrayable;
    use Serializable;

    private ?RequestInterface $radar = null;

    private array $params = [];

    private ?Collection $payload = null;

    private string $packer = PackerInterface::class;

    private string $direction = DirectionInterface::class;

    /**
     * @var array|Collection|MessageInterface
     */
    private $destination;

    private ?MessageInterface $destinationOrigin = null;

    public function getRadar(): ?RequestInterface
    {
        return $this->radar;
    }

    public function setRadar(RequestInterface $radar): Rocket
    {
        $this->radar = $radar;

        return $this;
    }

    public function getParams(): array
    {
        return $this->params;
    }

    public function setParams(array $params): Rocket
    {
        $this->params = $params;

        return $this;
    }

    public function mergeParams(array $params): Rocket
    {
        $this->params = array_merge($this->params, $params);

        return $this;
    }

    public function getPayload(): ?Collection
    {
        return $this->payload;
    }

    public function setPayload(Collection $payload): Rocket
    {
        $this->payload = $payload;

        return $this;
    }

    public function mergePayload(array $payload): Rocket
    {
        if (empty($this->payload)) {
            $this->payload = new Collection();
        }

        $this->payload = $this->payload->merge($payload);

        return $this;
    }

    public function getPacker(): string
    {
        return $this->packer;
    }

    public function setPacker(string $packer): Rocket
    {
        $this->packer = $packer;

        return $this;
    }

    public function getDirection(): string
    {
        return $this->direction;
    }

    public function setDirection(string $direction): Rocket
    {
        $this->direction = $direction;

        return $this;
    }

    /**
     * @return null|Collection|MessageInterface
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * @param Collection|MessageInterface $destination
     */
    public function setDestination($destination): Rocket
    {
        $this->destination = $destination;

        return $this;
    }

    public function getDestinationOrigin(): ?MessageInterface
    {
        return $this->destinationOrigin;
    }

    public function setDestinationOrigin(?MessageInterface $destinationOrigin): Rocket
    {
        $this->destinationOrigin = $destinationOrigin;

        return $this;
    }
}
