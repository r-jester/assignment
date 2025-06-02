<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Tenant;
use App\Models\Business;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::with(['tenant', 'business'])->paginate(10);
        return view('suppliers.index', compact('suppliers'));
    }

    public function create()
    {
        $tenants = Tenant::all();
        $businesses = Business::all();
        return view('suppliers.create', compact('tenants', 'businesses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => 'nullable|exists:tenants,id',
            'business_id' => 'required|exists:businesses,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        Supplier::create($validated);

        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully.');
    }

    public function show(Supplier $supplier)
    {
        $supplier->load(['tenant', 'business']);
        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier)
    {
        $tenants = Tenant::all();
        $businesses = Business::all();
        return view('suppliers.edit', compact('supplier', 'tenants', 'businesses'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'tenant_id' => 'nullable|exists:tenants,id',
            'business_id' => 'required|exists:businesses,id',
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $supplier->update($validated);

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully.');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully.');
    }
}