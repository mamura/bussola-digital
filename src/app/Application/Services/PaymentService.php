<?php

namespace App\Application\Services;

use App\Domain\Payment\Contracts\PaymentStrategyInterface;
use App\Models\Order;

class PaymentService
{
    public function __construct(protected PaymentStrategyInterface $strategy) {}

    public function handle(Order $order): Order
    {
        $total = $this->strategy->pay($order);

        $order->update([
            'status' => 'paid',
            'total'  => $total,
        ]);

        return $order->refresh();
    }
}
