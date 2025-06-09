<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Position;
use App\Models\Department;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role as SpatieRole;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    protected $actions = ['view', 'create', 'edit', 'delete'];

    protected function getModules()
    {
        $viewsPath = resource_path('views');
        $modules = [];

        $directories = File::directories($viewsPath);
        foreach ($directories as $directory) {
            $moduleName = basename($directory);
            if ($moduleName !== 'layouts' && $moduleName !== 'components' && $moduleName !== 'auth') {
                $modules[] = $moduleName;
            }
        }

        $modules = array_unique(array_merge($modules, ['permissions']));
        return $modules;
    }

    public function run()
    {
        $modules = $this->getModules();
        $permissions = ['manage-permissions'];

        foreach ($modules as $module) {
            foreach ($this->actions as $action) {
                $permissions[] = "$action-$module";
            }
        }

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $superAdminRole = SpatieRole::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        $superAdminRole->syncPermissions(Permission::all());

        $adminRole = SpatieRole::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $staffRole = SpatieRole::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $internRole = SpatieRole::firstOrCreate(['name' => 'intern', 'guard_name' => 'web']);
        $userRole = SpatieRole::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        $adminPermissions = ['manage-permissions'];
        foreach ($modules as $module) {
            $adminPermissions[] = "view-$module";
            $adminPermissions[] = "create-$module";
            $adminPermissions[] = "edit-$module";
        }
        $adminRole->syncPermissions($adminPermissions);

        $staffPermissions = [];
        foreach ($modules as $module) {
            $staffPermissions[] = "view-$module";
        }
        $staffRole->syncPermissions($staffPermissions);
        $internRole->syncPermissions($staffPermissions);
        $userRole->syncPermissions($staffPermissions);

        $position = Position::firstOrCreate(
            ['name' => 'Super Admin'],
            [
                'description' => 'System Super Administrator'
            ]
        );

        $department = Department::firstOrCreate(
            ['name' => 'Administration'],
            [
                'description' => 'System Administration'
            ]
        );

        // Ensure existing employees are deleted to control IDs
        Employee::truncate();

        // Superadmin with ID 1
        $employee1 = Employee::create([
            'id' => 1,
            'department_id' => $department->id,
            'position_id' => $position->id,
            'username' => 'jester',
            'password' => Hash::make('Jester'),
            'first_name' => 'Super',
            'last_name' => 'Jester',
            'email' => 'superjester@fake.com',
            'phone' => '1234567890',
            'hire_date' => now()->timezone('Asia/Phnom_Penh')->format('Y-m-d'),
            'salary' => 100000,
            'status' => 'active',
            'image' => 'employees/MacBookProM5Pro.jpeg',
        ]);
        $employee1->syncRoles(['superadmin']);

        // Admin with ID 2
        $employee2 = Employee::create([
            'id' => 2,
            'department_id' => $department->id,
            'position_id' => $position->id,
            'username' => 'admin',
            'password' => Hash::make('admin'),
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'email' => 'superadmin@fake.com',
            'phone' => '12345678',
            'hire_date' => now()->timezone('Asia/Phnom_Penh')->format('Y-m-d'),
            'salary' => 100000,
            'status' => 'active',
            'image' => 'employees/MacBookProM5Pro.jpeg'
        ]);
        $employee2->syncRoles(['admin']);

        // User with ID 3
        $employee3 = Employee::create([
            'id' => 3,
            'department_id' => $department->id,
            'position_id' => $position->id,
            'username' => 'user',
            'password' => Hash::make('user'),
            'first_name' => 'User',
            'last_name' => 'Normal',
            'email' => 'superuser@fake.com',
            'phone' => '1234567891',
            'hire_date' => now()->timezone('Asia/Phnom_Penh')->format('Y-m-d'),
            'salary' => 50000,
            'status' => 'active',
            'image' => 'employees/MacBookProM5Pro.jpeg'
        ]);
        $employee3->syncRoles(['user']);

        // Create a random user
        $faker = Faker::create();
        $randomEmployee = Employee::create([
            'department_id' => $department->id,
            'position_id' => $position->id,
            'username' => $faker->unique()->userName,
            'password' => Hash::make('password'),
            'first_name' => $faker->firstName,
            'last_name' => $faker->lastName,
            'email' => $faker->unique()->safeEmail,
            'phone' => $faker->phoneNumber,
            'hire_date' => now()->timezone('Asia/Phnom_Penh')->format('Y-m-d'),
            'salary' => $faker->numberBetween(30000, 80000),
            'status' => 'active',
            'image' => 'Uploads/employees/default.jpg'
        ]);
        $randomEmployee->syncRoles(['user']);

        $this->call([
            CategorySeeder::class,
            ProductSeeder::class,
            SalesSummarySeeder::class,
            InventorySummarySeeder::class,
            CurrencySeeder::class,
            CustomerSeeder::class,
            SupplierSeeder::class,
            EmployeeSeeder::class,
            PurchaseSeeder::class,
            ExpenseSeeder::class,
            SaleSeeder::class,
            TaxRateSeeder::class,
            UnitSeeder::class,
            PaymentMethodSeeder::class,
            PositionSeeder::class,
            DepartmentSeeder::class,
            RoleSeeder::class,
        ]);
    }
}

/*
 * To ensure the correct timezone, set the 'timezone' in config/app.php:
 * 'timezone' => 'Asia/Phnom_Penh',
 * After updating, clear the config cache:
 * php artisan config:clear
 * Verify the server timezone (e.g., on Linux: `timedatectl`) is set to Asia/Phnom_Penh or UTC+07:00.
 */