<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\Tenant;
use Illuminate\Database\Seeder;

class BusinessLocationSeeder extends Seeder
{
    public function run()
    {
        $tenant = Tenant::first() ?? Tenant::factory()->create();
        $business = Business::first() ?? Business::factory()->create(['tenant_id' => $tenant->id]);

        BusinessLocation::create([
            'tenant_id' => $tenant->id,
            'business_id' => $business->id,
            'name' => 'Drewside Branch',
            'address' => '514 Ziemann Vista, East Karleemouth, GA 05818-8200',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Or use factory for multiple records
        BusinessLocation::factory()->count(9)->create([
            'tenant_id' => $tenant->id,
            'business_id' => $business->id,
        ]);
    }
}