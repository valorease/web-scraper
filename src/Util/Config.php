<?php

namespace Util;

class Config
{
    private const string
        GLOBAL_CONFIG_FILE = __APP__ . '/config/global.php',
        LOCAL_CONFIG_FILE = __APP__ . '/config/local.php';

    private static ?array $config = null;

    public static function get(): array
    {
        if (empty(self::$config)) {
            self::$config = include file_exists(self::LOCAL_CONFIG_FILE)
                ? self::LOCAL_CONFIG_FILE
                : self::GLOBAL_CONFIG_FILE;
        }

        return self::$config;
    }
}
