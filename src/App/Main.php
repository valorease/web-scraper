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
                    "headers" => [
                        "Authorization" => "Bearer " . config("API", "TOKEN"),
                    ],
                ];

                $fetch = fetch(
                    config("API", "URL") . "/product/queue",
                    $options
                );

                if (!($fetch instanceof \Fetch\Http\Response)) {
                    throw new \Exception("Falha na requisição");
                }

                $product = $fetch->json();

                $product = new Product(...$product);

                if (
                    !empty($product->lastSearch)
                    && (new \DateTime($product->lastSearch))
                        ->diff(date_create())->h < 1
                ) {
                    continue;
                }

                $result = $product->parse($product->search());

                $result['prices'] = array_values($result['prices']);

                $result = [
                    "publicId" => $product->publicId,
                    ...$result,
                ];

                $options["method"] = "PUT";
                $options["body"] = $result;

                fetch(config("API", "URL") . "/product/queue", $options);

                Log::saveLocal('result', json_encode($options["body"]));
            } catch (\Exception $exception) {
                echo $exception->getMessage();
                Log::saveLocal("exception_error", $exception->getMessage());
            }

            sleep(5);
        }
    }

    public static function api(): never
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['slug'])) {
            http_response_code(400) && die;
        }

        if (empty($data['target'])) {
            http_response_code(400) && die;
        }

        if (!in_array($data['target'], ["ML" /*, "AM"*/])) {
            http_response_code(400) && die;
        }

        $product = new Product(
            null,
            $data['slug'],
            $data['target'],
            null,
            null
        );

        $result = $product->parse($product->search());

        $result['prices'] = array_values($result['prices']);

        ob_clean();
        header('Content-Type: application/json');
        echo json_encode($result, JSON_PRETTY_PRINT);

        die;
    }
}
