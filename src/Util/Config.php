<?php

namespace Util;

class Config
{
    private const string
        GLOBAL_CONFIG_FILE = __APP__ . '/config/global.php',
        LOCAL_CONFIG_FILE = __APP__ . '/config/local.php';

    private static ?array $config = null;

    public static function get(?string $key = null): mixed
    {
        if (empty(self::$config)) {
            self::$config = include file_exists(self::LOCAL_CONFIG_FILE)
                ? self::LOCAL_CONFIG_FILE
                : self::GLOBAL_CONFIG_FILE;
        }

        return empty($key) ? self::$config : self::$config[$key] ?? null;
    }
}