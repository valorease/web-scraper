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
            'span.andes-money-amount__fraction[aria-hidden="true"]:not(.poly-phrase-price)'
        );

        $content = $content->getIterator();

        $prices = [];

        foreach ($content as $node) {
            $closest = $node->closest('.ui-search-result__wrapper');

            if (
                empty($closest)
                || empty($node->closest('span.andes-money-amount.andes-money-amount--cents-superscript'))
                || empty($node->closest('div.poly-price__current'))
                || empty($node->closest('div.poly-content'))
            ) {
                continue;
            }

            $price = $node->textContent;
            $price = str_replace('.', '', $price);

            $cents = $node->parentElement->querySelector(
                'span.andes-money-amount__cents'
            );

            $price .= empty($cents->textContent) ? '.00' : ".{$cents->textContent}";

            try {
                $url = $closest
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
