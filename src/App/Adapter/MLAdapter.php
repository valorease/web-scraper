<?php

namespace App\Adapter;

use App\Adapter;

use Dom\HTMLDocument;

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

            $price = (float) $price;

            $prices[] = $price;
        }

        return $prices;
    }
}
