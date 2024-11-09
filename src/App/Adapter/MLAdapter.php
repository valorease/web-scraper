<?php

namespace App\Adapter;

use App\Adapter;

use Dom\HTMLDocument;
use Exception;

class MLAdapter extends Adapter
{
    public function prices(string $content): array
    {
        $document = HTMLDocument::createFromString($content);

        $content = $document->querySelectorAll(
            'span.andes-money-amount__fraction:not(.poly-phrase-price)'
        );

        $content = $content->getIterator();

        $prices = [];

        foreach ($content as $node) {
            $price = $node->textContent;
            $price = str_replace('.', '', $price);

            $cents = $node->parentElement->querySelector(
                'span.andes-money-amount__cents'
            );

            $price .= empty($cents->textContent) ? '.00' : ".{$cents->textContent}";

            try {
                $url = $node
                    ->closest('.ui-search-result__wrapper')
                    ->querySelector('a')
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
