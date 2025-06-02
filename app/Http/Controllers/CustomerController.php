<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Tenant;
use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Exceptions\UnauthorizedException;

class CustomerController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasPermissionTo('view-customers')) {
            throw UnauthorizedException::forPermissions(['view-customers']);
        }

        $customers = Customer::with(['tenant', 'business'])->paginate(10);
        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        if (!auth()->user()->hasPermissionTo('create-customers')) {
            throw UnauthorizedException::forPermissions(['create-customers']);
        }

        $tenants = Tenant::all();
        $businesses = Business::all();
        return view('customers.create', compact('tenants', 'businesses'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('create-customers')) {
            throw UnauthorizedException::forPermissions(['create-customers']);
        }

        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'business_id' => 'required|exists:businesses,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive,prospect',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('uploads/customers', 'public');
            $validated['image'] = $imagePath;
        }

        Customer::create($validated);

        return redirect()->route('customers.index')->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer)
    {
        if (!auth()->user()->hasPermissionTo('view-customers')) {
            throw UnauthorizedException::forPermissions(['view-customers']);
        }

        $customer->load(['tenant', 'business', 'leads', 'contacts', 'tasks', 'followUps']);
        return view('customers.show', compact('customer'));
    }

    public function edit(Customer $customer)
    {
        if (!auth()->user()->hasPermissionTo('edit-customers')) {
            throw UnauthorizedException::forPermissions(['edit-customers']);
        }

        $tenants = Tenant::all();
        $businesses = Business::all();
        return view('customers.edit', compact('customer', 'tenants', 'businesses'));
    }

    public function update(Request $request, Customer $customer)
    {
        if (!auth()->user()->hasPermissionTo('edit-customers')) {
            throw UnauthorizedException::forPermissions(['edit-customers']);
        }

        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'business_id' => 'required|exists:businesses,id',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|unique:customers,email,' . $customer->id,
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
            'status' => 'required|in:active,inactive,prospect',
            'image' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($customer->image) {
                Storage::disk('public')->delete($customer->image);
            }
            $imagePath = $request->file('image')->store('uploads/customers', 'public');
            $validated['image'] = $imagePath;
        }

        $customer->update($validated);

        return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        if (!auth()->user()->hasPermissionTo('delete-customers')) {
            throw UnauthorizedException::forPermissions(['delete-customers']);
        }

        if ($customer->image) {
            Storage::disk('public')->delete($customer->image);
        }
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'Customer deleted successfully.');
    }
}