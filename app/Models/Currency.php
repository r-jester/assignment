<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = ['tenant_id', 'code', 'name', 'symbol'];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}