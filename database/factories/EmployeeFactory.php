<?php

namespace Database\Factories;

use App\Models\Employee;
use App\Models\Tenant;
use App\Models\Business;
use App\Models\Department;
use App\Models\Position;
use App\Models\Role; // ✅ Add this line
use Illuminate\Database\Eloquent\Factories\Factory;

class EmployeeFactory extends Factory
{
    protected $model = Employee::class;

    public function definition()
    {
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        
        return [
            'tenant_id' => Tenant::factory(),
            'business_id' => Business::factory(),
            'department_id' => Department::factory(),
            'position_id' => Position::factory(),
            'role_id' => Role::factory(), // Add this line
            'username' => $this->faker->unique()->userName,
            'password' => bcrypt('password'),
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'name' => "$firstName $lastName", // ✅ Add this
            'email' => $this->faker->unique()->safeEmail,
            'hire_date' => $this->faker->date(),
            'salary' => $this->faker->numberBetween(30000, 100000),
            'status' => $this->faker->randomElement(['active', 'inactive', 'terminated']),
            'image' => 'uploads/employees/' . $this->faker->uuid . '.jpg',
        ];
    }
}
