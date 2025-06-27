<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function preview(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return response()->json(['message' => 'Carrinho vazio.'], 400);
        }

        $result = app(CheckoutService::class)->simulate($cart, $request->all());

        return response()->json($result);
    }

    public function store(Request $request)
    {
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return response()->json(['message' => 'Carrinho vazio.'], 400);
        }

        $result = app(CheckoutService::class)->checkout($cart, $request->all());

        session()->forget('cart');

        return response()->json([
            'message' => 'Compra finalizada com sucesso!',
            'dados' => $result
        ]);
    }
}
