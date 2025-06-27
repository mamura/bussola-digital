<?php

namespace App\Application\Services;

use App\Domain\Cart\Contracts\CartRepositoryInterface;

use App\Models\Order;
use Illuminate\Support\Facades\DB;

class CheckoutService
{
    public function __construct(
        protected CartRepositoryInterface $cartRepository
    ) {}
    
    public function fromCart(): Order
    {
        return DB::transaction(function () {
            $cart = $this->cartRepository->current();

            $order = Order::where('status', 'draft')
            ->orderByDesc('created_at')
            ->firstOrFail();

            $order->items()->delete();

            $total = 0;

            foreach ($cart->getItems() as $item) {
                $subtotal = $item->subtotal();
                $total += $subtotal;

                $order->items()->create([
                    'variant_id'   => $item->variantId,
                    'product_name' => $item->name,
                    'unit_price'   => $item->unitPrice,
                    'quantity'     => $item->quantity,
                    'total_price'  => $subtotal,
                ]);
            }

            $order->update([
                'total' => $total,
            ]);

            return $order->fresh('items');
        });
    }
}
