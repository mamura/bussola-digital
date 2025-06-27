<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Product;
use App\Models\ProductVariant;

class CheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create([
            'product_id'    => $product->id,
            'sku'           => 'SKU-TEST',
            'price'         => 50.00,
        ]);

        $this->postJson('/api/cart', [
            'variant_id' => $variant->id,
            'name'       => 'Produto Teste',
            'price'      => 50.00,
            'quantity'   => 2,
        ])->assertStatus(201);
    }

    public function test_it_previews_the_checkout_with_pix()
    {
        $response = $this->postJson('/api/checkout/preview', [
            'payment_method' => 'pix',
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'quantidade_itens',
                     'total_bruto',
                     'total_estimado',
                 ])
                 ->assertJsonFragment([
                     'quantidade_itens' => 1,
                     'total_bruto'      => 100.00,
                     'total_estimado'   => 90.00,
                 ]);
    }

    public function test_it_completes_checkout_with_pix(): void
    {
        $variant = ProductVariant::factory()->create([
            'price' => 300.00,
        ]);

        $this->postJson('/api/cart', [
            'variant_id' => $variant->id,
            'name'       => 'Produto Premium',
            'price'      => 300.00,
            'quantity'   => 1,
        ]);


        $response = $this->postJson('/api/checkout', [
            'payment_method' => 'pix',
        ]);

        $response->assertStatus(200)
                ->assertJsonStructure([
                    'message',
                    'order_id',
                    'valor_pago',
                    'status',
                ])
                ->assertJsonFragment([
                    'message'    => 'Pedido finalizado com sucesso',
                    'valor_pago' => "360",
                    'status'     => 'paid',
                ]);
    }


}
