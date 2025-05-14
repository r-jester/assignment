<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id', 'business_id', 'location_id', 'user_id', 'category_id',
        'amount', 'description', 'expense_date'
    ];

    protected $casts = [
        'expense_date' => 'date',
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

    public function user()
    {
        return $this->belongsTo(Employee::class, 'user_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}