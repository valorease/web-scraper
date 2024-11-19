<?php

namespace Util;

class Log
{
    public static function saveLocal(string $title, string $text): void
    {
        $file = __APP__ . '/tmp/log/' . time() . "_$title";

        if (!file_exists($file)) {
            mkdir(dirname($file), 0777, true);
        }

        file_put_contents($file, "$title\n\n$text");
    }
}