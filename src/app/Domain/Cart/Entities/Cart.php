<?php

namespace App\Domain\Cart\Entities;

use JsonSerializable;

class Cart implements JsonSerializable
{
    protected array $items = [];

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function addItem(CartItem $item): void
    {
        $this->items[] = $item;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function total(): float
    {
        return array_reduce($this->items, function ($carry, CartItem $item) {
            return $carry + $item->subtotal();
        }, 0);
    }

    public function isEmpty(): bool
    {
        return empty($this->items);
    }

    public function jsonSerialize(): mixed
    {
        return [
            'items' => $this->items,
            'total' => $this->total(),
        ];
    }
}
