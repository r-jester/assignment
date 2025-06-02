<?php

namespace Database\Factories;

use App\Models\Expense;
use App\Models\Tenant;
use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\Employee;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    protected $model = Expense::class;

    public function definition()
    {
        return [
            // 'tenant_id' => Tenant::factory(),
            // 'business_id' => Business::factory(),
            // 'location_id' => BusinessLocation::factory(),
            'user_id' => Employee::factory(),
            'category_id' => Category::factory(),
            'amount' => $this->faker->randomFloat(2, 10, 1000),
            'description' => $this->faker->sentence,
            'expense_date' => $this->faker->date(),
        ];
    }
}