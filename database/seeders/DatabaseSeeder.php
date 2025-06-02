<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role as SpatieRole;
use App\Models\Position;
use App\Models\Department;
use App\Models\Role;
use App\Models\Employee;
use App\Models\Tenant;
use App\Models\Business;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    protected $actions = ['view', 'create', 'edit', 'delete'];

    protected function getModules()
    {
        $controllersPath = app_path('Http/Controllers');
        $modules = [];

        $files = File::files($controllersPath);
        foreach ($files as $file) {
            $filename = $file->getFilenameWithoutExtension();
            if (str_ends_with($filename, 'Controller')) {
                $moduleName = str_replace('Controller', '', $filename);
                $moduleName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $moduleName));
                $moduleName = Str::plural($moduleName);
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

        $superAdminRole = SpatieRole::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $superAdminRole->syncPermissions(Permission::all());

        $adminRole = SpatieRole::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $staffRole = SpatieRole::firstOrCreate(['name' => 'staff', 'guard_name' => 'web']);
        $internRole = SpatieRole::firstOrCreate(['name' => 'intern', 'guard_name' => 'web']);

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

        $tenant = Tenant::firstOrCreate(
            ['name' => 'Default Tenant'],
            [
                'address' => '123 Main St',
                'city' => 'New York',
                'state' => 'NY',
                'zip' => '10001',
                'country' => 'USA',
                'phone' => '123-456-7890',
                'email' => 'contact@defaulttenant.com',
                'website' => 'https://defaulttenant.com',
            ]
        );

        $business = Business::firstOrCreate(
            ['name' => 'Default Business', 'tenant_id' => $tenant->id],
            [
                'tenant_id' => $tenant->id,
                'address' => '123 Business St',
                'city' => 'New York',
                'state' => 'NY',
                'zip' => '10001',
                'country' => 'USA',
                'phone' => '123-456-7890',
                'email' => 'contact@defaultbusiness.com',
                'website' => 'https://defaultbusiness.com',
            ]
        );

        $position = Position::firstOrCreate(
            ['name' => 'Super Admin'],
            [
                'tenant_id' => $tenant->id,
                'business_id' => $business->id,
                'description' => 'System Super Administrator'
            ]
        );

        $department = Department::firstOrCreate(
            ['name' => 'Administration'],
            [
                'tenant_id' => $tenant->id,
                'business_id' => $business->id,
                'description' => 'System Administration'
            ]
        );

        $role = Role::firstOrCreate(
            ['name' => 'Super Admin'],
            [
                'guard_name' => 'web',
                'description' => 'Has access to all system functions'
            ]
        );

        $employee = Employee::firstOrCreate(
            ['email' => 'superjester@fake.com'],
            [
                'tenant_id' => $tenant->id,
                'business_id' => $business->id,
                'department_id' => $department->id,
                'position_id' => $position->id,
                'role_id' => $role->id,
                'name' => 'Super Jester',
                'phone' => '1234567890',
                'username' => 'jester',
                'password' => bcrypt('Jester'),
                'first_name' => 'Super',
                'last_name' => 'Jester',
                'hire_date' => now(),
                'salary' => 100000,
                'status' => 'active',
                'image' => 'uploads/employees/default.jpg',
            ]
        );

        $employee->assignRole('super-admin');

        $this->call([
            TenantSeeder::class,
            BusinessSeeder::class,
            BusinessLocationSeeder::class,
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