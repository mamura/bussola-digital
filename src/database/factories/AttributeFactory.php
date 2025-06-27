<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Attribute;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttributeFactory extends Factory
{
    protected $model = Attribute::class;

    public function definition(): array
    {
        return [
            'category_id'   => Category::factory(),
            'name'          => $this->faker->unique()->word,
            'input_type'    => 'select',
        ];
    }
}
