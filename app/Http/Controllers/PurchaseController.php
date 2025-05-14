<?php

namespace App\Http\Controllers;

use App\Models\Purchase;
use App\Models\Tenant;
use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\Supplier;
use App\Models\Employee;
use App\Models\PurchaseItem;
use App\Models\Product;
use App\Models\TaxRate;
use Illuminate\Http\Request;

class PurchaseController extends Controller
{
    public function index()
    {
        $purchases = Purchase::with(['tenant', 'business', 'location', 'supplier', 'user'])->paginate(10);
        return view('purchases.index', compact('purchases'));
    }

    public function create()
    {
        $tenants = Tenant::all();
        $businesses = Business::all();
        $locations = BusinessLocation::all();
        $suppliers = Supplier::all();
        $employees = Employee::all();
        $products = Product::all();
        $taxRates = TaxRate::whereNull('end_date')->orWhere('end_date', '>=', now())->get();
        return view('purchases.create', compact('tenants', 'businesses', 'locations', 'suppliers', 'employees', 'products', 'taxRates'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'business_id' => 'required|exists:businesses,id',
            'location_id' => 'required|exists:business_locations,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'user_id' => 'required|exists:employees,id',
            'status' => 'required|in:completed,pending,cancelled',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate_id' => 'nullable|exists:tax_rates,id',
        ]);

        $totalAmount = 0;
        $taxAmount = 0;

        $purchase = Purchase::create([
            'tenant_id' => $validated['tenant_id'],
            'business_id' => $validated['business_id'],
            'location_id' => $validated['location_id'],
            'supplier_id' => $validated['supplier_id'],
            'user_id' => $validated['user_id'],
            'status' => $validated['status'],
            'total_amount' => 0,
            'tax_amount' => 0,
        ]);

        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            $quantity = $item['quantity'];
            $unitPrice = $item['unit_price'];
            $subtotal = $unitPrice * $quantity;
            $itemTaxAmount = 0;

            if (isset($item['tax_rate_id'])) {
                $taxRate = TaxRate::find($item['tax_rate_id']);
                $itemTaxAmount = $subtotal * ($taxRate->rate / 100);
            }

            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id' => $item['product_id'],
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
                'tax_amount' => $itemTaxAmount,
            ]);

            $totalAmount += $subtotal;
            $taxAmount += $itemTaxAmount;

            // Update stock
            $product->increment('stock_quantity', $quantity);
        }

        $purchase->update([
            'total_amount' => $totalAmount,
            'tax_amount' => $taxAmount,
        ]);

        return redirect()->route('purchases.index')->with('success', 'Purchase created successfully.');
    }

    public function show(Purchase $purchase)
    {
        $purchase->load(['tenant', 'business', 'location', 'supplier', 'user', 'purchaseItems.product']);
        return view('purchases.show', compact('purchase'));
    }

    public function edit(Purchase $purchase)
    {
        $tenants = Tenant::all();
        $businesses = Business::all();
        $locations = BusinessLocation::all();
        $suppliers = Supplier::all();
        $employees = Employee::all();
        $products = Product::all();
        $taxRates = TaxRate::whereNull('end_date')->orWhere('end_date', '>=', now())->get();
        $purchase->load('purchaseItems');
        return view('purchases.edit', compact('purchase', 'tenants', 'businesses', 'locations', 'suppliers', 'employees', 'products', 'taxRates'));
    }

    public function update(Request $request, Purchase $purchase)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'business_id' => 'required|exists:businesses,id',
            'location_id' => 'required|exists:business_locations,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'user_id' => 'required|exists:employees,id',
            'status' => 'required|in:completed,pending,cancelled',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate_id' => 'nullable|exists:tax_rates,id',
        ]);

        // Restore stock for existing items
        foreach ($purchase->purchaseItems as $item) {
            $product = Product::find($item->product_id);
            $product->decrement('stock_quantity', $item->quantity);
            $item->delete();
        }

        $totalAmount = 0;
        $taxAmount = 0;

        foreach ($validated['items'] as $item) {
            $product = Product::find($item['product_id']);
            $quantity = $item['quantity'];
            $unitPrice = $item['unit_price'];
            $subtotal = $unitPrice * $quantity;
            $itemTaxAmount = 0;

            if (isset($item['tax_rate_id'])) {
                $taxRate = TaxRate::find($item['tax_rate_id']);
                $itemTaxAmount = $subtotal * ($taxRate->rate / 100);
            }

            PurchaseItem::create([
                'purchase_id' => $purchase->id,
                'product_id' => $item['product_id'],
                'quantity' => $quantity,
                'unit_price' => $unitPrice,
                'subtotal' => $subtotal,
                'tax_amount' => $itemTaxAmount,
            ]);

            $totalAmount += $subtotal;
            $taxAmount += $itemTaxAmount;

            // Update stock
            $product->increment('stock_quantity', $quantity);
        }

        $purchase->update([
            'tenant_id' => $validated['tenant_id'],
            'business_id' => $validated['business_id'],
            'location_id' => $validated['location_id'],
            'supplier_id' => $validated['supplier_id'],
            'user_id' => $validated['user_id'],
            'status' => $validated['status'],
            'total_amount' => $totalAmount,
            'tax_amount' => $taxAmount,
        ]);

        return redirect()->route('purchases.index')->with('success', 'Purchase updated successfully.');
    }

    public function destroy(Purchase $purchase)
    {
        // Restore stock for items
        foreach ($purchase->purchaseItems as $item) {
            $product = Product::find($item->product_id);
            $product->decrement('stock_quantity', $item->quantity);
        }
        $purchase->purchaseItems()->delete();
        $purchase->delete();

        return redirect()->route('purchases.index')->with('success', 'Purchase deleted successfully.');
    }
}