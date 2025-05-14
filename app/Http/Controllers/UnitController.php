<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Tenant;
use App\Models\Business;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::with(['tenant', 'business'])->paginate(10);
        return view('units.index', compact('units'));
    }

    public function create()
    {
        $tenants = Tenant::all();
        $businesses = Business::all();
        return view('units.create', compact('tenants', 'businesses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => 'nullable|exists:tenants,id',
            'business_id' => 'required|exists:businesses,id',
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:50',
        ]);

        Unit::create($validated);

        return redirect()->route('units.index')->with('success', 'Unit created successfully.');
    }

    public function show(Unit $unit)
    {
        $unit->load(['tenant', 'business']);
        return view('units.show', compact('unit'));
    }

    public function edit(Unit $unit)
    {
        $tenants = Tenant::all();
        $businesses = Business::all();
        return view('units.edit', compact('unit', 'tenants', 'businesses'));
    }

    public function update(Request $request, Unit $unit)
    {
        $validated = $request->validate([
            'tenant_id' => 'nullable|exists:tenants,id',
            'business_id' => 'required|exists:businesses,id',
            'name' => 'required|string|max:255',
            'short_name' => 'required|string|max:50',
        ]);

        $unit->update($validated);

        return redirect()->route('units.index')->with('success', 'Unit updated successfully.');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();

        return redirect()->route('units.index')->with('success', 'Unit deleted successfully.');
    }
}