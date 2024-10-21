<?php

namespace App;

use App\Adapter\MLAdapter;

abstract class Adapter
{
    /**
     * @return string[]
     */
    abstract public function prices(string $content): array;

    public static function getAdapter(string $target): Adapter
    {
        return match ($target) {
            "ML" => new MLAdapter(),
            true => throw new \Exception(),
        };
    }

    public static function getUrl(string $target, string $slug): string
    {
        return match ($target) {
            "ML" => "https://lista.mercadolivre.com.br/$slug",
            true => throw new \Exception(),
        };
    }
}
