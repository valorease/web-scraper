<?php

namespace App;

class Product
{
    /**
     * @param string[] $targets
     */
    public function __construct(
        public readonly ?string $publicId,
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

    private static function headers(): array
    {
        return [
            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/121.0.0.0 Safari/537.36',
            'Accept-Encoding' => 'gzip, deflate',
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8',
            'Connection' => 'keep-alive',
            'Accept-Language' => 'en-GB,en;q=0.6',
            'Cookie' => 'zero-chakra-ui-color-mode=light-zero; AMP_MKTG_8f1ede8e9c=JTdCJTIycmVmZXJyZXIlMjIlM0ElMjJodHRwcyUzQSUyRiUyRnd3dy5nb29nbGUuY29tJTJGJTIyJTJDJTIycmVmZXJyaW5nX2RvbWFpbiUyMiUzQSUyMnd3dy5nb29nbGUuY29tJTIyJTdE; AMP_8f1ede8e9c=JTdCJTIyZGV2aWNlSWQlMjIlM0ElMjI1MjgxOGYyNC05ZGQ3LTQ5OTAtYjcxMC01NTY0NzliMzAwZmYlMjIlMkMlMjJzZXNzaW9uSWQlMjIlM0ExNzA4MzgxNTQ4ODQzJTJDJTIyb3B0T3V0JTIyJTNBZmFsc2UlMkMlMjJsYXN0RXZlbnRUaW1lJTIyJTNBMTcwODM4MjE1NTQ2MCUyQyUyMmxhc3RFdmVudElkJTIyJTNBNiU3RA==',
        ];
    }
}
