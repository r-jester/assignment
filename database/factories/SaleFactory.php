<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    public function definition()
    {
        $tenant = Tenant::first() ?? Tenant::factory()->create();
        $business = Business::first() ?? Business::factory()->create(['tenant_id' => $tenant->id]);
        $location = BusinessLocation::first() ?? BusinessLocation::factory()->create(['tenant_id' => $tenant->id, 'business_id' => $business->id]);

        return [
            'tenant_id' => $tenant->id,
            'business_id' => $business->id,
            'location_id' => $location->id,
            'customer_id' => Customer::factory(),
            'user_id' => Employee::factory(),
            'invoice_number' => 'INV-' . str_pad($this->faker->unique()->numberBetween(1, 1000), 4, '0', STR_PAD_LEFT),
            'total_amount' => $this->faker->randomFloat(2, 100, 10000),
            'tax_amount' => $this->faker->randomFloat(2, 0, 1000),
            'status' => $this->faker->randomElement(['completed', 'pending', 'cancelled']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}