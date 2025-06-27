<?php

namespace App\Domain\Product\Entities;

class Product
{
    public function __contruct(
        public readonly int $id,
        public string $name,
        public string $description,
        public Category $category,
        public array $attributes = [],
        public array $variants = []
    ) {}
}
