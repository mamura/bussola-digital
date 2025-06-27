<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Cart\Contracts\CartRepositoryInterface;
use App\Domain\Cart\Entities\Cart;
use App\Domain\Cart\Entities\CartItem;
use App\Models\Order;
use App\Models\OrderItem;

class EloquentCartRepository implements CartRepositoryInterface
{
    protected Order $order;

    public function __construct()
    {
        // Para este MVP usamos sempre o carrinho ID = 1
        $this->order = Order::firstOrCreate(
            ['id' => 1],
            ['status' => 'draft', 'total' => 0]
        );
    }

    public function current(): Cart
    {
        return $this->mapToCart($this->order->load('items'));
    }

    public function addItem(CartItem $item): Cart
    {
        $this->order->items()->create([
            'variant_id'   => $item->variantId,
            'product_name' => $item->name,
            'unit_price'   => $item->unitPrice,
            'quantity'     => $item->quantity,
            'total_price'  => $item->subtotal(),
        ]);

        return $this->current();
    }

    public function updateQty(int $itemId, int $qty): Cart
    {
        $orderItem = $this->order->items()->findOrFail($itemId);
        $orderItem->update([
            'quantity'    => $qty,
            'total_price' => $orderItem->unit_price * $qty,
        ]);

        $this->order->total = $this->order->items()->sum('total_price');
        $this->order->save();

        return $this->current();
    }

    public function removeItem(int $itemId): Cart
    {
        $this->order->items()->whereKey($itemId)->delete();

        $this->order->total = $this->order->items()->sum('total_price');
        $this->order->save();

        return $this->current();
    }

    public function clear(): void
    {
        $this->order->items()->delete();

        $this->order->total = 0;
        $this->order->save();
    }

    protected function mapToCart(Order $order): Cart
    {
        $items = $order->items->map(fn (OrderItem $oi) => new CartItem(
            $oi->variant_id,
            $oi->product_name,
            (float) $oi->unit_price,
            $oi->quantity
        ))->all();

        return new Cart($items);
    }
}
