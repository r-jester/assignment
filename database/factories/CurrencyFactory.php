<?php

namespace Database\Factories;

use App\Models\Currency;
use App\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

class CurrencyFactory extends Factory
{
    protected $model = Currency::class;

    public function definition()
    {
        return [
            'tenant_id' => Tenant::factory(),
            'code' => $this->faker->unique()->currencyCode,
            'name' => $this->faker->word,
            'symbol' => $this->faker->randomElement(['$', '€', '£', '¥']),
        ];
    }
}