<?php

namespace App\Http\Controllers;

use App\Models\PaymentMethod;
use App\Models\Tenant;
use App\Models\Business;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $paymentMethods = PaymentMethod::with(['tenant', 'business'])->paginate(10);
        return view('payment_methods.index', compact('paymentMethods'));
    }

    public function create()
    {
        $tenants = Tenant::all();
        $businesses = Business::all();
        return view('payment_methods.create', compact('tenants', 'businesses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => 'nullable|exists:tenants,id',
            'business_id' => 'required|exists:businesses,id',
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        PaymentMethod::create($validated);

        return redirect()->route('payment_methods.index')->with('success', 'Payment Method created successfully.');
    }

    public function show(PaymentMethod $paymentMethod)
    {
        $paymentMethod->load(['tenant', 'business']);
        return view('payment_methods.show', compact('paymentMethod'));
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        $tenants = Tenant::all();
        $businesses = Business::all();
        return view('payment_methods.edit', compact('paymentMethod', 'tenants', 'businesses'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'tenant_id' => 'nullable|exists:tenants,id',
            'business_id' => 'required|exists:businesses,id',
            'name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $paymentMethod->update($validated);

        return redirect()->route('payment_methods.index')->with('success', 'Payment Method updated successfully.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();

        return redirect()->route('payment_methods.index')->with('success', 'Payment Method deleted successfully.');
    }
}