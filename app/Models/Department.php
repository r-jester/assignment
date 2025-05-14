<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'business_id',
        'name',
        'description',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}