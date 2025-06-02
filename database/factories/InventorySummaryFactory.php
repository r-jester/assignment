<?php

namespace Database\Factories;

use App\Models\InventorySummary;
use App\Models\Tenant;
use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventorySummaryFactory extends Factory
{
    protected $model = InventorySummary::class;

    public function definition()
    {
        return [
            'tenant_id' => Tenant::factory(),
            'business_id' => Business::factory(),
            'location_id' => BusinessLocation::factory(),
            'product_id' => Product::factory(),
            'stock_quantity' => $this->faker->numberBetween(0, 100),
            'last_updated' => $this->faker->dateTimeThisYear(),
        ];
    }
}