<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Tenant;
use App\Models\Business;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition()
    {
        return [
            'tenant_id' => Tenant::factory(),
            'business_id' => Business::factory(),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'address' => $this->faker->address,
            'status' => $this->faker->randomElement(['active', 'inactive', 'prospect']),
            'image' => 'uploads/customers/' . $this->faker->uuid . '.jpg',
        ];
    }
}