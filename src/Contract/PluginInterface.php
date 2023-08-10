<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Contract;

use Closure;
use MiniPay\Rocket;

/**
 * Class PluginInterface
 * @package MiniPay\Contract
 */
interface PluginInterface
{
    public function assembly(Rocket $rocket, Closure $next): Rocket;
}
