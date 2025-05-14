<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\Employee;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PermissionController extends Controller
{
    protected $actions = ['view', 'create', 'edit', 'delete'];

    protected function getModules()
    {
        $controllersPath = app_path('Http/Controllers');
        $modules = [];

        $files = File::files($controllersPath);
        foreach ($files as $file) {
            $filename = $file->getFilenameWithoutExtension();
            if (str_ends_with($filename, 'Controller')) {
                $moduleName = str_replace('Controller', '', $filename);
                $moduleName = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $moduleName));
                $moduleName = Str::plural($moduleName);
                $modules[] = $moduleName;
            }
        }

        $modules = array_unique(array_merge($modules, ['permissions']));

        return $modules;
    }

    public function index(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('manage-permissions')) {
            throw UnauthorizedException::forPermissions(['manage-permissions']);
        }

        $roles = Role::all();
        $employees = Employee::all();
        $modules = $this->getModules();
        $actions = $this->actions;

        // Ensure all permissions exist
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                Permission::firstOrCreate(['name' => "$action-$module", 'guard_name' => 'web']);
            }
        }
        Permission::firstOrCreate(['name' => 'manage-permissions', 'guard_name' => 'web']);

        // Get selected entity
        $selectedType = $request->input('type', 'role');
        $selectedRoleId = $request->input('role_id', $roles->first()->id ?? null);
        $selectedEmployeeId = $request->input('employee_id', null);
        $selectedPermissions = [];

        if ($selectedType === 'role' && $selectedRoleId) {
            $role = Role::find($selectedRoleId);
            $selectedPermissions = $role ? $role->permissions->pluck('name')->toArray() : [];
        } elseif ($selectedType === 'employee' && $selectedEmployeeId) {
            $employee = Employee::find($selectedEmployeeId);
            $selectedPermissions = $employee ? $employee->permissions->pluck('name')->toArray() : [];
        }

        return view('permissions.index', compact(
            'roles',
            'employees',
            'modules',
            'actions',
            'selectedType',
            'selectedRoleId',
            'selectedEmployeeId',
            'selectedPermissions'
        ));
    }

    public function getPermissions(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('manage-permissions')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $type = $request->input('type');
        $id = $request->input('id');
        $permissions = [];

        if ($type === 'role' && $id) {
            $role = Role::find($id);
            $permissions = $role ? $role->permissions->pluck('name')->toArray() : [];
        } elseif ($type === 'employee' && $id) {
            $employee = Employee::find($id);
            $permissions = $employee ? $employee->permissions->pluck('name')->toArray() : [];
        }

        return response()->json(['permissions' => $permissions]);
    }

    public function update(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('manage-permissions')) {
            throw UnauthorizedException::forPermissions(['manage-permissions']);
        }

        $validated = $request->validate([
            'type' => 'required|in:role,employee',
            'role_id' => 'required_if:type,role|nullable|exists:roles,id',
            'employee_id' => 'required_if:type,employee|nullable|exists:employees,id',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        if ($validated['type'] === 'role' && $validated['role_id']) {
            $role = Role::findOrFail($validated['role_id']);
            $role->syncPermissions($validated['permissions'] ?? []);
        } elseif ($validated['type'] === 'employee' && $validated['employee_id']) {
            $employee = Employee::findOrFail($validated['employee_id']);
            $employee->syncPermissions($validated['permissions'] ?? []);
        }

        return redirect()->route('permissions.index', [
            'type' => $validated['type'],
            'role_id' => $validated['role_id'],
            'employee_id' => $validated['employee_id']
        ])->with('success', 'Permissions updated successfully.');
    }
}