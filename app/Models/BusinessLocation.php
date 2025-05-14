<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id', 'business_id', 'name', 'address'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class)->withDefault(['name' => 'None']);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function salesSummaries()
    {
        return $this->hasMany(SalesSummary::class);
    }

    public function inventorySummaries()
    {
        return $this->hasMany(InventorySummary::class);
    }
}