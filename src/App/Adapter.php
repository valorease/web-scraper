<?php

namespace App;

use App\Adapter\MLAdapter;

abstract class Adapter
{
    abstract public function prices(string $content): array;

    public static function filterPrices(float $average, array $prices): array
    {
        $prices = array_filter(
            $prices,
            function (float $price) use ($average): bool {
                return (($price * 100) / $average) >= 20.0;
            }
        );

        $currentAverage = array_reduce(
            $prices,
            function (float $carry, float $current): float {
                return $carry + $current;
            },
            0.0
        ) / count($prices);

        $prices = array_filter(
            $prices,
            function (float $price) use ($currentAverage): bool {
                return (($price * 100) / $currentAverage) >= 20.0;
            }
        );

        return $prices;
    }

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
