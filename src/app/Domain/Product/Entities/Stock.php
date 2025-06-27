<?php

namespace App\Domain\Product\Entities;

class Stock
{
    public function __construct(
        public readonly int $id,
        public int $quantity
    ) {}

    public function isAvailable(int $requestedQty): bool
    {
        return $this->quantity >= $requestedQty;
    }
}
