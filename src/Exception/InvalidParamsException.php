<?php
/**
 * This file is part of Mini.
 * @auth lupeng
 */
declare(strict_types=1);

namespace MiniPay\Exception;

use Throwable;

/**
 * Class InvalidParamsException
 * @package MiniPay\Exception
 */
class InvalidParamsException extends Exception
{
    /**
     * @param mixed $extra
     */
    public function __construct(int $code = self::PARAMS_ERROR, string $message = 'Params Error', $extra = null, Throwable $previous = null)
    {
        parent::__construct($message, $code, $extra, $previous);
    }
}
