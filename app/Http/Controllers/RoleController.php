<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (!auth()->user()->hasPermissionTo('view-roles')) {
            throw UnauthorizedException::forPermissions(['view-roles']);
        }

        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!auth()->user()->hasPermissionTo('create-roles')) {
            throw UnauthorizedException::forPermissions(['create-roles']);
        }

        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('create-roles')) {
            throw UnauthorizedException::forPermissions(['create-roles']);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'description' => 'nullable|string',
        ]);

        Role::create($validated);

        return redirect()->route('roles.index')->with('success', 'Role created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        if (!auth()->user()->hasPermissionTo('view-roles')) {
            throw UnauthorizedException::forPermissions(['view-roles']);
        }

        return view('roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        if (!auth()->user()->hasPermissionTo('edit-roles')) {
            throw UnauthorizedException::forPermissions(['edit-roles']);
        }

        return view('roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        if (!auth()->user()->hasPermissionTo('edit-roles')) {
            throw UnauthorizedException::forPermissions(['edit-roles']);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'description' => 'nullable|string',
        ]);

        $role->update($validated);

        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        if (!auth()->user()->hasPermissionTo('delete-roles')) {
            throw UnauthorizedException::forPermissions(['delete-roles']);
        }

        if ($role->employees()->count() > 0) {
            return redirect()->route('roles.index')->with('error', 'Cannot delete role with assigned employees.');
        }

        if ($role->name === 'Super Admin') {
            return redirect()->route('roles.index')->with('error', 'Cannot delete the Super Admin role.');
        }

        $role->delete();

        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }
}