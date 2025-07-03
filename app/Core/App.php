<?php

declare(strict_types=1);

namespace App\Core;

class App
{
    protected static $container = [];

    public static function bind(string $key, mixed $value): void
    {
        static::$container[$key] = $value;
    }

    public static function get(string $key): mixed
    {
        if (!array_key_exists($key, static::$container)) {
            throw new \Exception("Error: Key not found in container: {$key}");
        }

        return static::$container[$key];
    }
}
