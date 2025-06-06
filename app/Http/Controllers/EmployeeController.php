<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Department;
use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role as SpatieRole;

class EmployeeController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasPermissionTo('view-employees')) {
            abort(403, 'Unauthorized to view employee');
        }

        $query = Employee::with(['department', 'position'])->latest();

        if (!auth()->user()->hasRole('superadmin')) {
            $query->where('id', '!=', 1);
        }

        $employees = $query->paginate(10);

        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $departments = Department::all();
        $positions = Position::all();
        $spatieRoles = SpatieRole::where('name', '!=', 'superadmin')->get();
        return view('employees.create', compact('departments', 'positions', 'spatieRoles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'spatie_role' => 'required|exists:roles,name|not_in:superadmin',
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

        return redirect()->route('employees.index')->with('success', 'Employee created successfully');
    }

    public function show(Employee $employee)
    {
        if ($employee->id === 1 && !auth()->user()->hasRole('superadmin')) {
            abort(403, 'Unauthorized to view superadmin.');
        }

        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        if ($employee->id === 1 && !auth()->user()->hasRole('superadmin')) {
            abort(403, 'Unauthorized to edit superadmin.');
        }

        $departments = Department::all();
        $positions = Position::all();
        $spatieRoles = auth()->user()->hasRole('superadmin') 
            ? SpatieRole::all() 
            : SpatieRole::where('name', '!=', 'superadmin')->get();

        return view('employees.edit', compact('employee', 'departments', 'positions', 'spatieRoles'));
    }

    public function update(Request $request, Employee $employee)
    {
        if ($employee->id === 1 && !auth()->user()->hasRole('superadmin')) {
            abort(403, 'Unauthorized to update superadmin.');
        }

        $rules = [
            'department_id' => 'required|exists:departments,id',
            'position_id' => 'required|exists:positions,id',
            'spatie_role' => 'required|exists:roles,name' . (auth()->user()->hasRole('superadmin') ? '' : '|not_in:superadmin'),
            'username' => 'required|unique:employees,username,' . $employee->id,
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'phone' => 'nullable|string',
            'hire_date' => 'required|date',
            'salary' => 'required|numeric',
            'status' => 'required|in:active,inactive,terminated',
            'image' => 'nullable|image|max:2048',
        ];

        if (!$employee->hasRole('superadmin')) {
            $rules['password'] = 'nullable';
        }

        $validated = $request->validate($rules);

        if ($request->hasFile('image')) {
            if ($employee->image) {
                Storage::disk('public')->delete($employee->image);
            }
            $validated['image'] = $request->file('image')->store('employees', 'public');
        }

        if (!empty($validated['password']) && !$employee->hasRole('superadmin')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $employee->update($validated);
        $employee->syncRoles([$validated['spatie_role']]);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->id === 1) {
            return redirect()->route('employees.index')->with('error', 'Cannot delete employee.');
        }

        if (auth()->user()->hasRole('admin') && !auth()->user()->hasRole('superadmin')) {
            if ($employee->id === auth()->user()->id) {
                return redirect()->route('employees.index')->with('error', 'You cannot delete yourself.');
            }
            if ($employee->hasRole('admin') || $employee->hasRole('superadmin')) {
                return redirect()->route('employees.index')->with('error', 'You cannot delete another admin or superadmin.');
            }
        }

        if ($employee->image) {
            Storage::disk('public')->delete($employee->image);
        }

        $employee->roles()->detach();

        try {
            $employee->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Failed to delete employee record ID: ' . $employee->id, [
                'error' => $e->getMessage(),
                'sql' => 'DELETE FROM employees WHERE id = ' . $employee->id,
            ]);

            $referencingTable = null;
            $tables = DB::select('SELECT name FROM sqlite_master WHERE type="table" AND name NOT LIKE "sqlite_%"');
            foreach ($tables as $table) {
                $foreignKeys = DB::select('PRAGMA foreign_key_list(' . $table->name . ')');
                foreach ($foreignKeys as $fk) {
                    if ($fk->table === 'employees' && $fk->to === 'id') {
                        $referencingTable = $table->name;
                        Log::error('Found referencing table: ' . $referencingTable, [
                            'foreign_key' => $fk->from,
                            'employee_id' => $employee->id,
                        ]);
                        break 2;
                    }
                }
            }

            $errorMessage = $referencingTable
                ? "Cannot delete employee because it is referenced by records in the '$referencingTable' table. Please delete or reassign those records first."
                : 'Cannot delete employee due to related records in another table (e.g., sales, purchases, expenses). Please delete or reassign related records first.';

            return redirect()->route('employees.index')->with('error', $errorMessage);
        }

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully');
    }
}