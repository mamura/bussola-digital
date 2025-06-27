<?php

namespace App\Http\Controllers\Api;

use App\Application\Services\CartService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CartController extends Controller
{

    public function __construct(
        private CartService $service
    ){}

    public function index()
    {
        $cart = $this->service->list();
        return response()->json($cart);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'variant_id'    => 'required|integer',
            'name'          => 'required|string',
            'price'         => 'required|numeric|min:0',
            'quantity'      => 'required|integer|min:1',
        ]);

        $cart   = $this->service->add($data);

        return response()->json($cart, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cart = $this->service->update($id, $data['quantity']);

        return response()->json($cart);
    }

    public function destroy(int $id)
    {
        $cart = $this->service->remove($id);

        return response()->json($cart);
    }

    public function clear()
    {
        $cart = $this->service->clear();
        
        return response()->noContent();
    }
}
