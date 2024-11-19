<?php

namespace App;

class Product
{
    /**
     * @param string[] $targets
     */
    public function __construct(
        public readonly string $publicId,
        public readonly string $slug,
        public readonly string $target,
        public readonly ?float $average = null,
        public readonly ?string $lastSearch = null
    ) {
    }

    public function search(): array
    {
        $url = Adapter::getUrl($this->target, $this->slug);

        return [
            "url" => $url,
            "content" => fetch($url)->text(),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function parse(array $searchResult): array
    {
        $prices = Adapter::getAdapter($this->target)->prices(
            $searchResult["content"]
        );

        return [
            "target" => $this->target,
            "prices" => Adapter::filterPrices($this->average, $prices),
        ];
    }
}
