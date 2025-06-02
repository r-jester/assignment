<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Tenant;
use App\Models\Business;
use App\Models\Department;
use App\Models\Position;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role as SpatieRole;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['department', 'position', 'role'])
                            ->latest()
                            ->paginate(10); // Paginate with 10 items per page
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        // $tenants = Tenant::all();
        // $businesses = Business::all();
        $departments = Department::all();
        $positions = Position::all();
        $roles = Role::all();
        $spatieRoles = SpatieRole::all();

        return view('employees.create', compact('departments', 'positions', 'roles', 'spatieRoles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // 'tenant_id' => 'required|exists:tenants,id',
            // 'business_id' => 'required|exists:businesses,id',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'role_id' => 'required|exists:roles,id',
            'spatie_role' => 'required|exists:roles,name',
            'username' => 'required|unique:employees,username',
            'password' => 'required|confirmed|min:3',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:employees,email',
            'phone' => 'nullable|string',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric',
            'status' => 'required|in:active,inactive,terminated',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('employees', 'public');
        }

        $validated['password'] = Hash::make($validated['password']);

        $employee = Employee::create($validated);
        $employee->assignRole($validated['spatie_role']);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        // $tenants = Tenant::all();
        // $businesses = Business::all();
        $departments = Department::all();
        $positions = Position::all();
        $roles = Role::all();
        $spatieRoles = SpatieRole::all();

        return view('employees.edit', compact('employee', 'departments', 'positions', 'roles', 'spatieRoles'));
    }

    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            // 'tenant_id' => 'required|exists:tenants,id',
            // 'business_id' => 'required|exists:businesses,id',
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'role_id' => 'required|exists:roles,id',
            'spatie_role' => 'required|exists:roles,name',
            'username' => 'required|unique:employees,username,' . $employee->id,
            'password' => 'nullable|confirmed|min:3',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'nullable|string',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric',
            'status' => 'required|in:active,inactive,terminated',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($employee->image) {
                Storage::disk('public')->delete($employee->image);
            }
            $validated['image'] = $request->file('image')->store('employees', 'public');
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $employee->update($validated);
        $employee->syncRoles([$validated['spatie_role']]);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->image) {
            Storage::disk('public')->delete($employee->image);
        }
        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}