<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class Employee extends Authenticatable
{
    use HasFactory, HasRoles;

    protected $fillable = [
        // 'tenant_id',
        // 'business_id',
        'department_id',
        'position_id',
        'role_id',
        'username',
        'password',
        'first_name',  // Make sure these are here
        'last_name',   // and not 'name'
        'email',
        'phone',
        'hire_date',
        'salary',
        'status',
        'image'
    ];

    protected $hidden = [
        'password',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function position()
    {
        return $this->belongsTo(Position::class);
    }

    // You might want to keep this if you have a separate role_id field
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    // Remove this as it's now handled by the HasRoles trait
    // public function roles()
    // {
    //     return $this->belongsToMany(\Spatie\Permission\Models\Role::class);
    // }
}