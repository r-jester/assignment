<?php

namespace Database\Factories;

use App\Models\Business;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class BusinessFactory extends Factory
{
    protected $model = Business::class;

    public function definition()
    {
        return [
            'tenant_id' => Tenant::factory(),
            'name' => $this->faker->company,
            'description' => $this->faker->sentence,
        ];
    }
}