<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Plugin;

use Closure;
use Mini\Container\EntryNotFoundException;
use Psr\Http\Message\ResponseInterface;
use MiniPay\Contract\DirectionInterface;
use MiniPay\Contract\PackerInterface;
use MiniPay\Contract\PluginInterface;
use MiniPay\Exception\Exception;
use MiniPay\Exception\InvalidConfigException;
use MiniPay\Rocket;

/**
 * Class ParserPlugin
 * @package MiniPay\Plugin
 */
class ParserPlugin implements PluginInterface
{
    /**
     * @param Rocket $rocket
     * @param Closure $next
     * @return Rocket
     * @throws EntryNotFoundException
     * @throws InvalidConfigException
     */
    public function assembly(Rocket $rocket, Closure $next): Rocket
    {
        /* @var Rocket $rocket */
        $rocket = $next($rocket);

        /* @var ResponseInterface $response */
        $response = $rocket->getDestination();

        return $rocket->setDestination(
            $this->getDirection($rocket)->parse($this->getPacker($rocket), $response)
        );
    }

    /**
     * @param Rocket $rocket
     * @return DirectionInterface
     * @throws InvalidConfigException
     */
    protected function getDirection(Rocket $rocket): DirectionInterface
    {
        $packer = app($rocket->getDirection());

        $packer = is_string($packer) ? app($packer) : $packer;

        if (!$packer instanceof DirectionInterface) {
            throw new InvalidConfigException(Exception::INVALID_PARSER);
        }

        return $packer;
    }

    /**
     * @param Rocket $rocket
     * @return PackerInterface
     * @throws InvalidConfigException
     * @throws EntryNotFoundException
     */
    protected function getPacker(Rocket $rocket): PackerInterface
    {
        $packer = app()->get($rocket->getPacker());

        $packer = is_string($packer) ? app($packer) : $packer;

        if (!$packer instanceof PackerInterface) {
            throw new InvalidConfigException(Exception::INVALID_PACKER);
        }

        return $packer;
    }
}
