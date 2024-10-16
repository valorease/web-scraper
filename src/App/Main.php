<?php

namespace App;

use App\Product;
use Util\Log;

class Main
{
    public static function app(): never
    {
        while (true) {
            try {
                $options = [
                    'headers' => [
                        'Authorization' => 'Bearer ' . config('API', 'TOKEN')
                    ],
                ];

                $fetch = fetch(config('API', 'URL'), $options);

                if (!($fetch instanceof \Fetch\Http\Response)) {
                    throw new \Exception('Falha na requisição');
                }

                $product = $fetch->json();
                $product = new Product(...$product);

                $result = $product->parse($product->search());

                $options['method'] = 'POST';
                $options['body'] = $result;

                fetch(config('API', 'URL'), $options);
            } catch (\Exception $exception) {
                echo $exception->getMessage();
                Log::saveLocal('exception_error', $exception->getMessage());
            }

            sleep(10);
        }
    }
}