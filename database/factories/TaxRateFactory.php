<?php

namespace Database\Factories;

use App\Models\TaxRate;
use App\Models\Tenant;
use App\Models\Business;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaxRateFactory extends Factory
{
    protected $model = TaxRate::class;

    public function definition()
    {
        return [
            'tenant_id' => $this->faker->boolean(50) ? Tenant::factory() : null,
            'business_id' => Business::factory(),
            'name' => $this->faker->word . ' Tax',
            'rate' => $this->faker->randomFloat(2, 0, 20),
        ];
    }
}