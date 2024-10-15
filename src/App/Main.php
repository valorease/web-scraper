<?php

namespace App;

use App\Product;

class Main
{

    public static function app(): void
    {
        // $options = [
        //     'headers' => [
        //         'Authorization' => "Bearer $token"
        //     ],
        // ];

        // $product = fetch(self::URL_API, $options)->json();

        $product = [
            'id' => 1,
            'targets' => ['ML'],
            'slug' => 'iphone-15'
        ];

        if (!is_array($product)) {
            return;
        }

        $product = new Product(...$product);

        $contents = $product->search();
        $result = $product->parse($contents);

        var_dump($result);
    }
}