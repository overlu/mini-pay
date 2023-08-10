<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Exception;

use Throwable;

/**
 * Class InvalidConfigException
 * @package MiniPay\Exception
 */
class InvalidConfigException extends Exception
{
    /**
     * @param mixed $extra
     */
    public function __construct(int $code = self::CONFIG_ERROR, string $message = 'Config Error', $extra = null, Throwable $previous = null)
    {
        parent::__construct($message, $code, $extra, $previous);
    }
}
