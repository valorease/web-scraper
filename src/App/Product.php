<?php

namespace App;

class Product
{
    public function __construct(
        public readonly int $id,
        public readonly string $slug,
        public readonly array $targets,
    ) {
    }

    public function search(): array
    {
        $result = [];

        foreach ($this->targets as $target) {
            $url = Adapter::getUrl($target, $this->slug);

            $result[$target] = [
                'url' => $url,
                'content' => fetch($url)->text()
            ];
        }

        return $result;
    }

    public function parse(array $searchResult): array
    {
        $result = [];

        foreach ($searchResult as $target => $data) {
            $result[] = [
                'target' => $target,
                'url' => $data['url'],
                'prices' => Adapter::getAdapter($target)->prices($data['content'])
            ];
        }

        return $result;
    }
}