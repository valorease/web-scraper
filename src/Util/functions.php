<?php

if (!function_exists('config')) {
    function config(string ...$keys): mixed
    {
        $config = '\Util\Config'::get();

        if (empty($keys)) {
            return $config;
        }

        $id = serialize($keys);

        if (!empty('\Util\MemoryCache'::get("get_config_$id"))) {
            return '\Util\MemoryCache'::get("get_config_$id");
        }

        foreach ($keys as $key) {
            if (empty($config[$key])) {
                return null;
            }

            $config = $config[$key];
        }

        '\Util\MemoryCache'::set("get_config_$id", $config);

        return $config;
    }
}