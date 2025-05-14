<?php

namespace Database\Seeders;

use App\Models\SalesSummary;
use App\Models\BusinessLocation;
use Illuminate\Database\Seeder;

class SalesSummarySeeder extends Seeder
{
    public function run()
    {
        BusinessLocation::factory()->count(5)->create(); // Problematic if tenant_id is missing
        SalesSummary::factory()->count(10)->create();
    }
}