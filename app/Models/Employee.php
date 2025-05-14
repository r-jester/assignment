<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Employee extends Authenticatable
{
    use HasFactory, HasRoles;

    protected $fillable = [
        'tenant_id', 'business_id', 'department_id', 'position_id', 'role_id',
        'name', 'phone', 'username', 'password', 'first_name', 'last_name',
        'email', 'hire_date', 'salary', 'status', 'image'
    ];

    protected $hidden = ['password'];

    protected $guard_name = 'web';

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

    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}