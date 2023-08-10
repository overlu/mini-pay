<?php
/**
 * This file is part of mini-pay.
 * @auth lupeng
 * @date 2023/8/8 18:41
 */
declare(strict_types=1);

namespace MiniPay;

/**
 * @method static void alert($message, array $context = [], string $module = '')
 * @method static void critical($message, array $context = [], string $module = '')
 * @method static void debug($message, array $context = [], string $module = '')
 * @method static void emergency($message, array $context = [], string $module = '')
 * @method static void error($message, array $context = [], string $module = '')
 * @method static void info($message, array $context = [], string $module = '')
 * @method static void notice($message, array $context = [], string $module = '')
 * @method static void warning($message, array $context = [], string $module = '')
 */
class Logger
{
    private const RFC_5424_LEVELS = [
        'debug' => 7,
        'info' => 6,
        'notice' => 5,
        'warning' => 4,
        'error' => 3,
        'critical' => 2,
        'alert' => 1,
        'emergency' => 0,
    ];

    /**
     * @return array
     */
    public static function getConfig(): array
    {
        return config('pay.logger', []);
    }

    public static function __callStatic($name, $arguments)
    {
        $config = self::getConfig();
        if (isset($config['enable']) && !$config['enable']) {
            return;
        }
        $level = $config['level'] ?? 'warning';

        if (self::RFC_5424_LEVELS[$name] > self::RFC_5424_LEVELS[$level]) {
            return;
        }
        $arguments[1] = $arguments[1] ?? [];
        $arguments[2] = 'pay';
        \Mini\Facades\Logger::log(...$arguments);
    }
}
