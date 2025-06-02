<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'address'
    ];

    // public function tenant()
    // {
    //     return $this->belongsTo(Tenant::class)->withDefault(['name' => 'None']);
    // }

    // public function business()
    // {
    //     return $this->belongsTo(Business::class);
    // }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }
}