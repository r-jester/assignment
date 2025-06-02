<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Tenant;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function index()
    {
        $currencies = Currency::with('tenant')->paginate(10);
        return view('currencies.index', compact('currencies'));
    }

    public function create()
    {
        $tenants = Tenant::all();
        return view('currencies.create', compact('tenants'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'code' => 'required|string|size:3|unique:currencies',
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10',
        ]);

        Currency::create($validated);

        return redirect()->route('currencies.index')->with('success', 'Currency created successfully.');
    }

    public function show(Currency $currency)
    {
        $currency->load('tenant');
        return view('currencies.show', compact('currency'));
    }

    public function edit(Currency $currency)
    {
        $tenants = Tenant::all();
        return view('currencies.edit', compact('currency', 'tenants'));
    }

    public function update(Request $request, Currency $currency)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'code' => 'required|string|size:3|unique:currencies,code,' . $currency->id,
            'name' => 'required|string|max:255',
            'symbol' => 'required|string|max:10',
        ]);

        $currency->update($validated);

        return redirect()->route('currencies.index')->with('success', 'Currency updated successfully.');
    }

    public function destroy(Currency $currency)
    {
        $currency->delete();

        return redirect()->route('currencies.index')->with('success', 'Currency deleted successfully.');
    }
}