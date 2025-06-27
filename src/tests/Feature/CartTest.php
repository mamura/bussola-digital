<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate');
    }

    public function test_it_lists_an_empty_cart()
    {
        $response = $this->getJson('/api/cart');

        $response->assertStatus(200)
                 ->assertJson([
                     'items' => [],
                     'total' => 0,
                 ]);
    }

    public function test_it_adds_an_item_to_the_cart()
    {
        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create([
            'product_id' => $product->id,
            'price' => 59.9,
        ]);

        $payload = [
            'variant_id' => $variant->id,
            'name' => 'Camiseta Básica',
            'price' => 59.9,
            'quantity' => 2,
        ];

        $response = $this->postJson('/api/cart', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment([
                     'variantId' => $variant->id,
                     'name' => 'Camiseta Básica',
                     'unitPrice' => 59.9,
                     'quantity' => 2,
                 ]);
    }

    public function test_it_updates_item_quantity_in_the_cart()
    {
        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create(['product_id' => $product->id, 'price' => 40]);

        $this->postJson('/api/cart', [
            'variant_id' => $variant->id,
            'name' => 'Camisa Polo',
            'price' => 40,
            'quantity' => 1,
        ]);

        $response = $this->patchJson('/api/cart/1', [
            'quantity' => 3,
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['quantity' => 3]);
    }

    public function test_it_removes_an_item_from_the_cart()
    {
        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create(['product_id' => $product->id, 'price' => 80]);

        $this->postJson('/api/cart', [
            'variant_id' => $variant->id,
            'name' => 'Bermuda Jeans',
            'price' => 80,
            'quantity' => 1,
        ]);

        $response = $this->deleteJson('/api/cart/1');

        $response->assertStatus(200)
                 ->assertJsonMissing(['variantId' => $variant->id]);
    }

    public function test_it_clears_the_cart()
    {
        $product = Product::factory()->create();
        $variant = ProductVariant::factory()->create(['product_id' => $product->id, 'price' => 30]);

        $this->postJson('/api/cart', [
            'variant_id' => $variant->id,
            'name' => 'Meia',
            'price' => 30,
            'quantity' => 2,
        ]);

        $response = $this->deleteJson('/api/cart/clear');

        $response->assertNoContent();

        $this->getJson('/api/cart')
             ->assertJson(['items' => [], 'total' => 0]);
    }
}
