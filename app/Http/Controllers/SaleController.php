<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Tenant;
use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Product;
use App\Models\TaxRate;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;

class SaleController extends Controller
{
    public function index()
    {
        if (!auth()->user()->hasPermissionTo('view-sales')) {
            throw UnauthorizedException::forPermissions(['view-sales']);
        }

        $sales = Sale::with(['tenant', 'business', 'customer'])->paginate(10);
        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        if (!auth()->user()->hasPermissionTo('create-sales')) {
            throw UnauthorizedException::forPermissions(['create-sales']);
        }

        $tenants = Tenant::all();
        $businesses = Business::all();
        $locations = BusinessLocation::all();
        $customers = Customer::all();
        $employees = Employee::all();
        $products = Product::all();
        $taxRates = TaxRate::all();

        return view('sales.create', compact('tenants', 'businesses', 'locations', 'customers', 'employees', 'products', 'taxRates'));
    }

    public function store(Request $request)
    {
        if (!auth()->user()->hasPermissionTo('create-sales')) {
            throw UnauthorizedException::forPermissions(['create-sales']);
        }

        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'business_id' => 'required|exists:businesses,id',
            'location_id' => 'required|exists:business_locations,id',
            'customer_id' => 'nullable|exists:customers,id',
            'user_id' => 'required|exists:employees,id',
            'status' => 'required|in:completed,pending,cancelled',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.tax_rate_id' => 'nullable|exists:tax_rates,id',
        ]);

        $totalAmount = 0;
        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            $quantity = $item['quantity'];
            $subtotal = $product->price * $quantity;
            $taxRate = isset($item['tax_rate_id']) ? TaxRate::find($item['tax_rate_id']) : null;
            $taxAmount = $taxRate ? ($subtotal * $taxRate->rate / 100) : 0;
            $totalAmount += $subtotal + $taxAmount;
        }

        $sale = Sale::create([
            'tenant_id' => $validated['tenant_id'],
            'business_id' => $validated['business_id'],
            'location_id' => $validated['location_id'],
            'customer_id' => $validated['customer_id'],
            'user_id' => $validated['user_id'],
            'status' => $validated['status'],
            'total_amount' => $totalAmount,
        ]);

        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            $quantity = $item['quantity'];
            $unit_price = $product->price;
            $subtotal = $unit_price * $quantity;
            $taxRate = isset($item['tax_rate_id']) ? TaxRate::find($item['tax_rate_id']) : null;
            $tax_amount = $taxRate ? ($subtotal * $taxRate->rate / 100) : 0;

            SaleItem::create([
                'sale_id' => $sale->id,
                'product_id' => $item['product_id'],
                'quantity' => $quantity,
                'unit_price' => $unit_price,
                'subtotal' => $subtotal,
                'tax_amount' => $tax_amount,
                'tax_rate_id' => $item['tax_rate_id'] ?? null,
            ]);
        }

        return redirect()->route('sales.index')->with('success', 'Sale created successfully.');
    }

    public function show(Sale $sale)
    {
        if (!auth()->user()->hasPermissionTo('view-sales')) {
            throw UnauthorizedException::forPermissions(['view-sales']);
        }

        $sale->load('saleItems.product', 'saleItems.taxRate', 'tenant', 'business', 'location', 'customer', 'user');
        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        if (!auth()->user()->hasPermissionTo('edit-sales')) {
            throw UnauthorizedException::forPermissions(['edit-sales']);
        }

        $sale->load('saleItems.product', 'saleItems.taxRate');
        $tenants = Tenant::all();
        $businesses = Business::all();
        $locations = BusinessLocation::all();
        $customers = Customer::all();
        $employees = Employee::all();
        $products = Product::all();
        $taxRates = TaxRate::all();

        return view('sales.edit', compact('sale', 'tenants', 'businesses', 'locations', 'customers', 'employees', 'products', 'taxRates'));
    }

    public function update(Request $request, Sale $sale)
    {
        if (!auth()->user()->hasPermissionTo('edit-sales')) {
            throw UnauthorizedException::forPermissions(['edit-sales']);
        }

        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'business_id' => 'required|exists:businesses,id',
            'location_id' => 'required|exists:business_locations,id',
            'customer_id' => 'nullable|exists:customers,id',
            'user_id' => 'required|exists:employees,id',
            'status' => 'required|in:completed,pending,cancelled',
            'items' => 'required|array',
            'items.*.id' => 'nullable|exists:sale_items,id',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.tax_rate_id' => 'nullable|exists:tax_rates,id',
        ]);

        $totalAmount = 0;
        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            $quantity = $item['quantity'];
            $subtotal = $product->price * $quantity;
            $taxRate = isset($item['tax_rate_id']) ? TaxRate::find($item['tax_rate_id']) : null;
            $taxAmount = $taxRate ? ($subtotal * $taxRate->rate / 100) : 0;
            $totalAmount += $subtotal + $taxAmount;
        }

        $sale->update([
            'tenant_id' => $validated['tenant_id'],
            'business_id' => $validated['business_id'],
            'location_id' => $validated['location_id'],
            'customer_id' => $validated['customer_id'],
            'user_id' => $validated['user_id'],
            'status' => $validated['status'],
            'total_amount' => $totalAmount,
        ]);

        $existingItemIds = $sale->saleItems->pluck('id')->toArray();
        $submittedItemIds = array_filter(array_column($validated['items'], 'id'));

        $itemsToDelete = array_diff($existingItemIds, $submittedItemIds);
        if (!empty($itemsToDelete)) {
            SaleItem::whereIn('id', $itemsToDelete)->delete();
        }

        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            $quantity = $item['quantity'];
            $unit_price = $product->price;
            $subtotal = $unit_price * $quantity;
            $taxRate = isset($item['tax_rate_id']) ? TaxRate::find($item['tax_rate_id']) : null;
            $tax_amount = $taxRate ? ($subtotal * $taxRate->rate / 100) : 0;

            $saleItemData = [
                'sale_id' => $sale->id,
                'product_id' => $item['product_id'],
                'quantity' => $quantity,
                'unit_price' => $unit_price,
                'subtotal' => $subtotal,
                'tax_amount' => $tax_amount,
                'tax_rate_id' => $item['tax_rate_id'] ?? null,
            ];

            if (isset($item['id']) && $item['id']) {
                SaleItem::where('id', $item['id'])->update($saleItemData);
            } else {
                SaleItem::create($saleItemData);
            }
        }

        return redirect()->route('sales.index')->with('success', 'Sale updated successfully.');
    }

    public function destroy(Sale $sale)
    {
        if (!auth()->user()->hasPermissionTo('delete-sales')) {
            throw UnauthorizedException::forPermissions(['delete-sales']);
        }

        $sale->saleItems()->delete();
        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully.');
    }
}