<?php

namespace WebScraper;

use WebScraper\Adapter\MLAdapter;

abstract class Adapter
{
    abstract public function prices(string $content): array;

    public static function getAdapter(string $target): Adapter
    {
        return match ($target) {
            'ML' => new MLAdapter(),
            true => throw new \Exception()
        };
    }
}