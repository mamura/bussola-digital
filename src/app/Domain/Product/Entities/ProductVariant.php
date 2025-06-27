<?php

namespace App\Domain\Product\Entities;

class ProductVariant
{
    public function __construct(
        public readonly int $id,
        public string $sku,
        public float $price,
        public array $attributes = [],
        public ?Stock $stock = null
    ) {}

    public function isInStock(): bool
    {
        return $this->stock?->quantity > 0;
    }
}
