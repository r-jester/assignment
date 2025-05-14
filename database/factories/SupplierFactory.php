<?php

namespace Database\Factories;

use App\Models\Supplier;
use App\Models\Tenant;
use App\Models\Business;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition()
    {
        return [
            'tenant_id' => $this->faker->boolean(50) ? Tenant::factory() : null,
            'business_id' => Business::factory(),
            'name' => $this->faker->company,
            'email' => $this->faker->unique()->companyEmail,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
        ];
    }
}