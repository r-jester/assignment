<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventorySummary extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id', 'business_id', 'location_id', 'product_id',
        'stock_quantity', 'last_updated'
    ];

    protected $casts = [
        'last_updated' => 'datetime',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function location()
    {
        return $this->belongsTo(BusinessLocation::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}