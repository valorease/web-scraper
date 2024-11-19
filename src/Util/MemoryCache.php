<?php

namespace Util;

class MemoryCache
{
    private static array $cache;

    public static function set(string $key, mixed $value): void
    {
        self::$cache[$key] = $value;
    }

    public static function get(string $key): mixed
    {
        return self::$cache[$key] ?? null;
    }
}