<?php

namespace WebScraper;

class Product
{
    public function __construct(
        public readonly int $id,
        public readonly string $slug,
        public readonly array $targets,
    ) {
    }

    public function search()
    {
    }
}