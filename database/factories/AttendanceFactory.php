<?php

namespace Database\Factories;

use App\Models\Attendance;
use App\Models\Tenant;
use App\Models\Business;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class AttendanceFactory extends Factory
{
    protected $model = Attendance::class;

    public function definition()
    {
        $date = $this->faker->dateTimeBetween('-1 month', 'now');
        $checkIn = Carbon::instance($date)->setTime(8, 0); // Example: 8 AM
        $checkOut = Carbon::instance($date)->setTime(17, 0); // Example: 5 PM

        return [
            'tenant_id' => $this->faker->boolean(50) ? Tenant::factory() : null,
            'business_id' => Business::factory(),
            'employee_id' => Employee::factory(),
            'check_in' => $checkIn,
            'check_out' => $this->faker->boolean(80) ? $checkOut : null,
            'date' => $date->format('Y-m-d'),
        ];
    }
}