<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Sale;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    public function run()
    {
        $tenant = Tenant::first() ?? Tenant::factory()->create();
        $business = Business::first() ?? Business::factory()->create(['tenant_id' => $tenant->id]);
        $location = BusinessLocation::first() ?? BusinessLocation::factory()->create(['tenant_id' => $tenant->id, 'business_id' => $business->id]);

        Sale::create([
            'tenant_id' => $tenant->id,
            'business_id' => $business->id,
            'location_id' => $location->id,
            'customer_id' => Customer::factory()->create()->id,
            'user_id' => Employee::factory()->create()->id,
            'invoice_number' => 'INV-0001',
            'total_amount' => 8079.99,
            'tax_amount' => 937.77,
            'status' => 'completed',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Or use factory for multiple records
        Sale::factory()->count(9)->create([
            'tenant_id' => $tenant->id,
            'business_id' => $business->id,
            'location_id' => $location->id,
            'invoice_number' => fn() => 'INV-' . str_pad(rand(2, 1000), 4, '0', STR_PAD_LEFT),
        ]);
    }
}