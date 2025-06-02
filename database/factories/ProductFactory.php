<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Tenant;
use App\Models\Business;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            // 'tenant_id' => $this->faker->boolean(50) ? Tenant::factory() : null,
            // 'business_id' => Business::factory(),
            'category_id' => Category::factory(),
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'stock_quantity' => $this->faker->numberBetween(0, 100),
            'sku' => $this->faker->unique()->bothify('SKU-####'),
            'barcode' => $this->faker->unique()->ean13,
            'image' => $this->faker->imageUrl(640, 480, 'products'),
        ];
    }
}