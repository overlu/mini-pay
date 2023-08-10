<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Event;

use Psr\Http\Message\ServerRequestInterface;
use MiniPay\Rocket;

/**
 * Class CallbackReceived
 * @package MiniPay\Event
 */
class CallbackReceived extends Event
{
    public string $provider;

    public ?array $params = null;

    /**
     * @var null|array|ServerRequestInterface
     */
    public $contents;

    /**
     * @param null|array|ServerRequestInterface $contents
     */
    public function __construct(string $provider, $contents, ?array $params = null, ?Rocket $rocket = null)
    {
        $this->provider = $provider;
        $this->contents = $contents;
        $this->params = $params;

        parent::__construct($rocket);
    }
}
