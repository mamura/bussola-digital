<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Stock;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
{
    Category::factory()
        ->count(3)
        ->has(
            Product::factory()
                ->count(5)
                ->has(
                    ProductVariant::factory()
                        ->count(2)
                        ->has(Stock::factory(), 'stock'),
                    'variants'
                ),
            'products'
        )
        ->create();
}

}
