<?php

namespace App\Domain\Payment\Contracts;

use App\Models\Order;

interface PaymentStrategyInterface
{
    public function pay(Order $order): float;
}
