<?php

namespace App\Infrastructure\Repositories;

use App\Domain\Cart\Contracts\CartRepositoryInterface;
use App\Domain\Cart\Entities\Cart;
use App\Domain\Cart\Entities\CartItem;
use App\Models\Order;
use App\Models\OrderItem;

class EloquentCartRepository implements CartRepositoryInterface
{
    public function current(): Cart
    {
        $order = $this->getDraftOrder();

        return $this->mapToCart($order->load('items'));
    }

    public function addItem(CartItem $item): Cart
    {
        $order = $this->getDraftOrder();

        $order->items()->create([
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
        $order = $this->getDraftOrder();
        
        $orderItem = $order->items()->findOrFail($itemId);
        $orderItem->update([
            'quantity'    => $qty,
            'total_price' => $orderItem->unit_price * $qty,
        ]);

        $order->update([
            'total' => $order->items()->sum('total_price'),
        ]);

        return $this->current();
    }

    public function removeItem(int $itemId): Cart
    {
        $order = $this->getDraftOrder();

        $order->items()->whereKey($itemId)->delete();

        $order->update([
            'total' => $order->items()->sum('total_price'),
        ]);

        return $this->current();
    }

    public function clear(): void
    {
        $order = $this->getDraftOrder();

        $order->items()->delete();

        $order->update(['total' => 0]);
    }

    protected function getDraftOrder(): Order
    {
        return Order::where('status', 'draft')->orderByDesc('created_at')->firstOrCreate(
            ['status' => 'draft'],
            ['total' => 0]
        );
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
