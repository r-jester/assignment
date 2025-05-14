<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusinessLocationFactory extends Factory
{
    public function definition()
    {
        $tenant = Tenant::first() ?? Tenant::factory()->create();
        $business = Business::first() ?? Business::factory()->create(['tenant_id' => $tenant->id]);

        return [
            'tenant_id' => $tenant->id,
            'business_id' => $business->id,
            'name' => $this->faker->company . ' Branch',
            'address' => $this->faker->address,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}