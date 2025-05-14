<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id', 'business_id', 'location_id', 'customer_id', 'user_id',
        'total_amount', 'tax_amount', 'status'
    ];

    protected $casts = [
        'status' => 'string',
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

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(Employee::class, 'user_id');
    }

    public function saleItems()
    {
        return $this->hasMany(SaleItem::class);
    }
}