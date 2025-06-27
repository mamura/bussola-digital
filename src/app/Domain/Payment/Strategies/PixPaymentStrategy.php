<?php

namespace App\Domain\Payment\Strategies;

use App\Domain\Payment\Contracts\PaymentStrategyInterface;
use App\Models\Order;

class PixPaymentStrategy implements PaymentStrategyInterface
{
    public function pay(Order $order): float
    {
        return round($order->total * 0.9, 2);
    }
}
