<?php

namespace Database\Factories;

use App\Models\PaymentMethod;
use App\Models\Tenant;
use App\Models\Business;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentMethodFactory extends Factory
{
    protected $model = PaymentMethod::class;

    public function definition()
    {
        return [
            'tenant_id' => $this->faker->boolean(50) ? Tenant::factory() : null,
            'business_id' => Business::factory(),
            'name' => $this->faker->randomElement(['Cash', 'Credit Card', 'Bank Transfer', 'PayPal', 'Mobile Payment']),
            'is_active' => $this->faker->boolean(80),
        ];
    }
}