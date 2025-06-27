<?php

namespace Database\Factories;

use App\Models\Attribute;
use App\Models\AttributeOption;
use Illuminate\Database\Eloquent\Factories\Factory;

class AttributeOptionFactory extends Factory
{
    protected $model = AttributeOption::class;

    public function definition(): array
    {
        return [
            'attribute_id'  => Attribute::factory(),
            'value'         => ucfirst($this->faker->word),
        ];
    }
}
