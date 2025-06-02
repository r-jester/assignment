<?php

namespace Database\Factories;

use App\Models\Unit;
use App\Models\Tenant;
use App\Models\Business;
use Illuminate\Database\Eloquent\Factories\Factory;

class UnitFactory extends Factory
{
    protected $model = Unit::class;

    public function definition()
    {
        $name = $this->faker->word;
        return [
            'tenant_id' => $this->faker->boolean(50) ? Tenant::factory() : null,
            'business_id' => Business::factory(),
            'name' => $name,
            'short_name' => strtoupper(substr($name, 0, 3)),
        ];
    }
}