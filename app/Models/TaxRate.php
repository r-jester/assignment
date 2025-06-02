<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaxRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'rate'
    ];

    // public function tenant()
    // {
    //     return $this->belongsTo(Tenant::class)->withDefault(['name' => 'None']);
    // }

    // public function business()
    // {
    //     return $this->belongsTo(Business::class);
    // }
}