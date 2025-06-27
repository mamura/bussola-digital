<?php

namespace Database\Factories;

use App\Models\ProductVariant;
use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\Factory;

class StockFactory extends Factory
{
    protected $model = Stock::class;

    public function definition(): array
    {
        return [
            'variant_id'    => ProductVariant::factory(),
            'quantity'      => $this->faker->numberBetween(1, 50),
        ];
    }
}
