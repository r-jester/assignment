<?php

namespace Database\Factories;

use App\Models\Purchase;
use App\Models\Tenant;
use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\Supplier;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseFactory extends Factory
{
    protected $model = Purchase::class;

    public function definition()
    {
        return [
            'tenant_id' => Tenant::factory(),
            'business_id' => Business::factory(),
            'location_id' => BusinessLocation::factory(),
            'supplier_id' => Supplier::factory(),
            'user_id' => Employee::factory(),
            'total_amount' => $this->faker->randomFloat(2, 100, 10000),
            'tax_amount' => $this->faker->randomFloat(2, 0, 1000),
            'status' => $this->faker->randomElement(['completed', 'pending', 'cancelled']),
        ];
    }
}