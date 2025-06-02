<?php

namespace App\Http\Controllers;

use App\Models\Business;
// use App\Models\Tenant;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    public function index()
    {
        // $businesses = Business::with('tenant')->paginate(10);
        $businesses = Business::paginate(10);
        return view('businesses.index', compact('businesses'));
    }

    public function create()
    {
        // $tenants = Tenant::all();
        return view('businesses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // 'tenant_id' => 'required|exists:tenants,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Business::create($validated);

        return redirect()->route('businesses.index')->with('success', 'Business created successfully.');
    }

    public function show(Business $business)
    {
        // $business->load('tenant');
        return view('businesses.show');
    }

    public function edit(Business $business)
    {
        // $tenants = Tenant::all();
        return view('businesses.edit', compact('business'));
    }

    public function update(Request $request, Business $business)
    {
        $validated = $request->validate([
            // 'tenant_id' => 'required|exists:tenants,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $business->update($validated);

        return redirect()->route('businesses.index')->with('success', 'Business updated successfully.');
    }

    public function destroy(Business $business)
    {
        $business->delete();

        return redirect()->route('businesses.index')->with('success', 'Business deleted successfully.');
    }
}