<?php

namespace Database\Factories;

use App\Models\SalesSummary;
use App\Models\Tenant;
use App\Models\Business;
use App\Models\BusinessLocation;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalesSummaryFactory extends Factory
{
    protected $model = SalesSummary::class;

    public function definition()
    {
        return [
            'tenant_id' => Tenant::factory(),
            'business_id' => Business::factory(),
            'location_id' => BusinessLocation::factory(),
            'sale_date' => $this->faker->date(),
            'total_sales' => $this->faker->randomFloat(2, 100, 10000),
            'total_tax' => $this->faker->randomFloat(2, 10, 1000),
            'total_quantity' => $this->faker->numberBetween(10, 100),
        ];
    }
}