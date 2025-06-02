<?php

namespace Database\Factories;

use App\Models\Category;
// use App\Models\Tenant;
// use App\Models\Business;
use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition()
    {
        return [
            // 'tenant_id' => Tenant::factory(),
            // 'business_id' => Business::factory(),
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
        ];
    }
}