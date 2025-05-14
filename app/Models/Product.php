<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id', 'business_id', 'category_id', 'name', 'description',
        'price', 'stock_quantity', 'sku', 'barcode', 'image'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class)->withDefault(['name' => 'None']);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function inventorySummaries()
    {
        return $this->hasMany(InventorySummary::class);
    }
}