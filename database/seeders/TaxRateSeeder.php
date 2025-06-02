<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaxRate;
use App\Models\Business;
use App\Models\Tenant;

class TaxRateSeeder extends Seeder
{
    public function run()
    {
        $tenant = Tenant::first(); // or create one
        $business = Business::first(); // or create one

        $taxRates = [
            ['rate' => 5, 'name' => '5% Tax'],
            ['rate' => 10, 'name' => '10% Tax'],
            ['rate' => 15, 'name' => '15% Tax'],
        ];

        foreach ($taxRates as $tax) {
            TaxRate::firstOrCreate(
                ['rate' => $tax['rate'], 'business_id' => $business->id],
                [
                    'tenant_id' => $tenant->id,
                    'name' => $tax['name'],
                ]
            );
        }
    }
}
