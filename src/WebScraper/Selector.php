<?php

namespace WebScraper;

use Dom\HTMLDocument;

class Selector
{
    public static function price(string $content)
    {
        $document = HTMLDocument::createFromString($content);

        $content = $document->querySelectorAll(
            'span.andes-money-amount__fraction'
        );

        $content = $content->getIterator();

        $prices = [];

        foreach ($content as $node) {
            $prices[] = $node->textContent;
        }

        var_dump($prices);
    }

}