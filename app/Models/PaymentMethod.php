<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id', 'business_id', 'name', 'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class)->withDefault(['name' => 'None']);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}