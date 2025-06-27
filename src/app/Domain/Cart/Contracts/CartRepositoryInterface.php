<?php

namespace App\Domain\Cart\Contracts;

use App\Domain\Cart\Entities\Cart;
use App\Domain\Cart\Entities\CartItem;

interface CartRepositoryInterface
{
    public function current(): Cart;
    public function addItem(CartItem $item): Cart;
    public function updateQty(int $itemId, int $qty): Cart;
    public function removeItem(int $itemId): Cart;
    public function clear(): void;
}
