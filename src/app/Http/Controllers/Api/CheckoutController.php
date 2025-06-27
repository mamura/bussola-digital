<?php

namespace App\Http\Controllers\Api;

use App\Application\Services\CheckoutService;
use App\Application\Services\PaymentService;
use App\Domain\Cart\Contracts\CartRepositoryInterface;
use App\Domain\Payment\Contracts\PaymentStrategyInterface;
use App\Domain\Payment\Strategies\CreditCardInstallmentsStrategy;
use App\Domain\Payment\Strategies\CreditCardOneShotStrategy;
use App\Domain\Payment\Strategies\PixPaymentStrategy;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        protected CartRepositoryInterface $cartRepository,
    ) {}
    
    public function preview(Request $request)
    {
        $validated = $request->validate([
            'payment_method'    => 'required|in:pix,credito_avista,credito_parcelado',
            'installments'      => 'nullable|integer|min:2|max:12',
        ]);

        $cart   = $this->cartRepository->current();
        $order  = new Order([
            'status' => 'draft',
            'total'  => 0,
        ]);
        $total  = 0;
        
        foreach ($cart->getItems() as $item) {
            $subtotal = $item->subtotal();
            $total += $subtotal;

            $order->items->push(new OrderItem([
                'variant_id'   => $item->variantId,
                'product_name' => $item->name,
                'unit_price'   => $item->unitPrice,
                'quantity'     => $item->quantity,
                'total_price'  => $subtotal,
            ]));
        }

        $order->total = $total;

        $strategy       = $this->resolveStrategy($validated);
        $estimatedTotal = $strategy->pay($order);

        return response()->json([
            'quantidade_itens' => count($cart->getItems()),
            'total_bruto'      => $total,
            'total_estimado'   => $estimatedTotal,
        ]);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'payment_method'    => 'required|in:pix,credito_avista,credito_parcelado',
            'installments'      => 'nullable|integer|min:2|max:12',
        ]);

        $checkoutService    = new CheckoutService($this->cartRepository);
        $order              = $checkoutService->fromCart();
        $strategy           = $this->resolveStrategy($validated);
        $paymentService     = new PaymentService($strategy);
        $order              = $paymentService->handle($order);

        return response()->json([
            'message'     => 'Pedido finalizado com sucesso',
            'order_id'    => $order->id,
            'valor_pago'  => $order->total,
            'status'      => $order->status,
        ]);
    }

    private function resolveStrategy(array $data): PaymentStrategyInterface
    {
        return match ($data['payment_method']) {
            'pix'               => new PixPaymentStrategy(),
            'credito_avista'    => new CreditCardOneShotStrategy(),
            'credito_parcelado' => new CreditCardInstallmentsStrategy($data['installments'] ?? 2),
        };
    }


}
