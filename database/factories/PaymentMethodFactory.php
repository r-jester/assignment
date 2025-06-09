<?php

namespace Database\Factories;

use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentMethodFactory extends Factory
{
    protected $model = PaymentMethod::class;

    public function definition()
    {
        return [
            'name' => $this->faker->randomElement(['Cash', 'Credit Card', 'Bank Transfer', 'PayPal', 'Mobile Payment']),
            'is_active' => $this->faker->boolean(80),
        ];
    }
}