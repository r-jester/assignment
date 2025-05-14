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
use Spatie\Permission\Exceptions\UnauthorizedException;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->hasPermissionTo('view-employees')) {
            throw UnauthorizedException::forPermissions(['view-employees']);
        }

        $employees = Employee::with(['tenant', 'business', 'department', 'position'])->paginate(10);
        return view('employees.index', compact('employees'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->hasPermissionTo('create-employees')) {
            throw UnauthorizedException::forPermissions(['create-employees']);
        }

        $tenants = Tenant::all();
        $businesses = Business::all();
        $departments = Department::all();
        $positions = Position::all();
        $roles = Role::all();
        $spatieRoles = auth()->user()->hasRole('super-admin')
            ? SpatieRole::where('name', '!=', 'super-admin')->get()
            : SpatieRole::whereIn('name', ['staff', 'intern'])->get();

        return view('employees.create', compact('tenants', 'businesses', 'departments', 'positions', 'roles', 'spatieRoles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('create-employees')) {
            throw UnauthorizedException::forPermissions(['create-employees']);
        }

        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'business_id' => 'required|exists:businesses,id',
            'department_id' => 'nullable|exists:departments,id',
            'position_id' => 'nullable|exists:positions,id',
            'role_id' => 'required|exists:roles,id',
            'spatie_role' => 'required|exists:roles,name',
            'username' => 'required|string|max:255|unique:employees,username',
            'password' => 'required|string|min:6|confirmed',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:employees,email',
            'phone' => 'nullable|string|max:20',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|numeric',
            'status' => 'required|in:active,inactive,terminated',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads/employees', 'public');
            $validated['image'] = $imagePath;
        }

        $employee = Employee::create($validated);
        $employee->assignRole($request->spatie_role);

        return redirect()->route('employees.index')->with('success', 'Employee created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        if (!auth()->user()->hasPermissionTo('view-employees')) {
            throw UnauthorizedException::forPermissions(['view-employees']);
        }

        $employee->load(['tenant', 'business', 'department', 'position', 'role']);
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        if (!auth()->user()->hasPermissionTo('edit-employees')) {
            throw UnauthorizedException::forPermissions(['edit-employees']);
        }

        $tenants = Tenant::all();
        $businesses = Business::all();
        $departments = Department::all();
        $positions = Position::all();
        $roles = Role::all();
        $spatieRoles = auth()->user()->hasRole('super-admin')
            ? SpatieRole::where('name', '!=', 'super-admin')->get()
            : SpatieRole::whereIn('name', ['staff', 'intern'])->get();

        return view('employees.edit', compact('employee', 'tenants', 'businesses', 'departments', 'positions', 'roles', 'spatieRoles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        if (!auth()->user()->hasPermissionTo('edit-employees')) {
            throw UnauthorizedException::forPermissions(['edit-employees']);
        }

        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'business_id' => 'required|exists:businesses,id',
            'department_id' => 'nullable|exists:departments,id',
            'position_id' => 'nullable|exists:positions,id',
            'role_id' => 'required|exists:roles,id',
            'spatie_role' => 'required|exists:roles,name',
            'username' => 'required|string|max:255|unique:employees,username,' . $employee->id,
            'password' => 'nullable|string|min:6|confirmed',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:employees,email,' . $employee->id,
            'phone' => 'nullable|string|max:20',
            'hire_date' => 'nullable|date',
            'salary' => 'nullable|numeric',
            'status' => 'required|in:active,inactive,terminated',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        if ($request->hasFile('image')) {
            if ($employee->image) {
                Storage::disk('public')->delete($employee->image);
            }
            $imagePath = $request->file('image')->store('uploads/employees', 'public');
            $validated['image'] = $imagePath;
        }

        $employee->update($validated);
        $employee->syncRoles([$request->spatie_role]);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        if (!auth()->user()->hasPermissionTo('delete-employees')) {
            throw UnauthorizedException::forPermissions(['delete-employees']);
        }

        if ($employee->hasRole('super-admin')) {
            return redirect()->route('employees.index')->with('error', 'Cannot delete Super Admin.');
        }

        if ($employee->image) {
            Storage::disk('public')->delete($employee->image);
        }

        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }
}