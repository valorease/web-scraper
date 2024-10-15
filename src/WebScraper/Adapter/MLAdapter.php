<?php

namespace WebScraper\Adapter;

use WebScraper\Adapter;

use Dom\HTMLDocument;

class MLAdapter extends Adapter
{
    public function prices(string $content): array
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

        return $prices;
    }
}