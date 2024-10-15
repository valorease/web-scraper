<?php

namespace WebScraper;

use WebScraper\Product;

class Main
{
    public const URL_API = 'http://localhost:9090';

    public static function app(): void
    {
        $content = fetch('https://lista.mercadolivre.com.br/iphone-15#D[A:iphone%2015]')->text();
        Selector::price($content);

        return;
        $token = '';

        $options = [
            'headers' => [
                'Authorization' => "Bearer $token"
            ],
        ];

        $product = fetch(self::URL_API, $options)->json();

        if (!is_array($product)) {
            return;
        }

        $product = new Product(...$product);

        if (empty($product->urls)) {
            self::byUrl();
        }
    }

    public static function byUrl()
    {

    }
}