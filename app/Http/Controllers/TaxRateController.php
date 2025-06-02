<?php

namespace App\Http\Controllers;

use App\Models\TaxRate;
use App\Models\Tenant;
use App\Models\Business;
use Illuminate\Http\Request;

class TaxRateController extends Controller
{
    public function index()
    {
        $taxRates = TaxRate::with(['tenant', 'business'])->paginate(10);
        return view('tax_rates.index', compact('taxRates'));
    }

    public function create()
    {
        $tenants = Tenant::all();
        $businesses = Business::all();
        return view('tax_rates.create', compact('tenants', 'businesses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => 'nullable|exists:tenants,id',
            'business_id' => 'required|exists:businesses,id',
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0|max:100',
        ]);

        TaxRate::create($validated);

        return redirect()->route('tax_rates.index')->with('success', 'Tax Rate created successfully.');
    }

    public function show(TaxRate $taxRate)
    {
        $taxRate->load(['tenant', 'business']);
        return view('tax_rates.show', compact('taxRate'));
    }

    public function edit(TaxRate $taxRate)
    {
        $tenants = Tenant::all();
        $businesses = Business::all();
        return view('tax_rates.edit', compact('taxRate', 'tenants', 'businesses'));
    }

    public function update(Request $request, TaxRate $taxRate)
    {
        $validated = $request->validate([
            'tenant_id' => 'nullable|exists:tenants,id',
            'business_id' => 'required|exists:businesses,id',
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0|max:100',
        ]);

        $taxRate->update($validated);

        return redirect()->route('tax_rates.index')->with('success', 'Tax Rate updated successfully.');
    }

    public function destroy(TaxRate $taxRate)
    {
        $taxRate->delete();

        return redirect()->route('tax_rates.index')->with('success', 'Tax Rate deleted successfully.');
    }
}