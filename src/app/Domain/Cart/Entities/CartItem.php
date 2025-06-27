<?php

namespace App\Domain\Cart\Entities;

class CartItem
{
    public function __construct(
        public readonly int $variantId,
        public readonly string $name,
        public readonly float $unitPrice,
        public int $quantity
    ){}

    public function subtotal(): float
    {
        return $this->unitPrice * $this->quantity;
    }
}
