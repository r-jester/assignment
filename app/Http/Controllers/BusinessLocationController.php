<?php

namespace App\Http\Controllers;

use App\Models\BusinessLocation;
use App\Models\Tenant;
use App\Models\Business;
use Illuminate\Http\Request;

class BusinessLocationController extends Controller
{
    public function index()
    {
        $businessLocations = BusinessLocation::with(['tenant', 'business'])->paginate(10);
        return view('business_locations.index', compact('businessLocations'));
    }

    public function create()
    {
        $tenants = Tenant::all();
        $businesses = Business::all();
        return view('business_locations.create', compact('tenants', 'businesses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => 'nullable|exists:tenants,id',
            'business_id' => 'required|exists:businesses,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string',
        ]);

        BusinessLocation::create($validated);

        return redirect()->route('business_locations.index')->with('success', 'Business Location created successfully.');
    }

    public function show(BusinessLocation $businessLocation)
    {
        $businessLocation->load(['tenant', 'business']);
        return view('business_locations.show', compact('businessLocation'));
    }

    public function edit(BusinessLocation $businessLocation)
    {
        $tenants = Tenant::all();
        $businesses = Business::all();
        return view('business_locations.edit', compact('businessLocation', 'tenants', 'businesses'));
    }

    public function update(Request $request, BusinessLocation $businessLocation)
    {
        $validated = $request->validate([
            'tenant_id' => 'nullable|exists:tenants,id',
            'business_id' => 'required|exists:businesses,id',
            'name' => 'required|string|max:255',
            'address' => 'required|string',
        ]);

        $businessLocation->update($validated);

        return redirect()->route('business_locations.index')->with('success', 'Business Location updated successfully.');
    }

    public function destroy(BusinessLocation $businessLocation)
    {
        $businessLocation->delete();

        return redirect()->route('business_locations.index')->with('success', 'Business Location deleted successfully.');
    }
}