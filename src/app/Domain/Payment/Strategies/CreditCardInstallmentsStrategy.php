<?php

namespace App\Domain\Payment\Strategies;

use App\Domain\Payment\Contracts\PaymentStrategyInterface;
use App\Models\Order;

class CreditCardInstallmentsStrategy implements PaymentStrategyInterface
{
    public function __construct(protected int $installments) {}

    public function pay(Order $order): float
    {
        $p = $order->total;
        $i = 0.01;
        $n = $this->installments;

        $m = $p * pow(1 + $i, $n);

        return round($m, 2);
    }
}
