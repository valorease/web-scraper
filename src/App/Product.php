<?php

namespace App;

class Product
{
    /**
     * @param string[] $targets
     */
    public function __construct(
        public readonly int $id,
        public readonly string $slug,
        public readonly array $targets,
        public readonly float $average
    ) {}

    public function search(): array
    {
        $result = [];

        foreach ($this->targets as $target) {
            $url = Adapter::getUrl($target, $this->slug);

            $result[$target] = [
                "url" => $url,
                "content" => fetch($url)->text(),
            ];
        }

        return $result;
    }
    /**
     * @param array<int,mixed> $searchResult
     * @return array<int,array<string,mixed>>
     */
    public function parse(array $searchResult): array
    {
        $result = [];

        foreach ($searchResult as $target => $data) {
            $prices = Adapter::getAdapter($target)->prices($data["content"]);

            $result[] = [
                "target" => $target,
                "url" => $data["url"],
                "prices" => Adapter::filterPrices($this->average, $prices),
            ];
        }

        return $result;
    }
}
