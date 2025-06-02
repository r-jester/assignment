<?php

namespace Database\Seeders;

use App\Models\SaleItem;
use Illuminate\Database\Seeder;

class SaleItemSeeder extends Seeder
{
    public function run()
    {
        SaleItem::factory()->count(20)->create();
    }
}