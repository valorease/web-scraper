<?php

namespace App\Adapter;

use App\Adapter;

use Dom\HTMLDocument;
use Exception;

class AMAdapter extends Adapter
{
    public function prices(string $content): array
    {
        $document = HTMLDocument::createFromString($content);

        $content = $document->querySelectorAll(
            '.src__Text-sc-154pg0p-0.styles__PromotionalPrice-sc-yl2rbe-0.dthYGD.list-price'
        );

        $content = $content->getIterator();

        $prices = [];

        foreach ($content as $node) {
            $price = $node->textContent;

            $price = str_replace('.', '', $price);
            $price = str_replace(',', '.', $price);

            $closest = $node->closest('a.inStockCard__Link-sc-1ngt5zo-1.JOEpk');

            try {
                $url = $closest
                    ->attributes['href']
                    ->textContent;
            } catch (Exception $ex) {
                $url = null;
            }

            $price = (float) $price;

            $prices[] = [
                "price" => $price,
                "url" => $url
            ];
        }

        return $prices;
    }
}
