<?php

namespace App\Http\Controllers;

use App\Models\InventorySummary;
use App\Models\InventoryAdjustment;
use App\Models\Tenant;
use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\Product;
use Illuminate\Http\Request;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Support\Facades\DB;

class InventorySummaryController extends Controller
{
    public function index()
    {
        // if (!auth()->user()->hasPermissionTo('view-inventory-summaries')) {
        //     throw UnauthorizedException::forPermissions(['view-inventory-summaries']);
        // }

        $inventorySummaries = InventorySummary::with(['tenant', 'business', 'location', 'product'])->paginate(10);
        return view('inventory_summaries.index', compact('inventorySummaries'));
    }

    public function create()
    {
        // if (!auth()->user()->hasPermissionTo('create-inventory-summaries')) {
        //     throw UnauthorizedException::forPermissions(['create-inventory-summaries']);
        // }

        $tenants = Tenant::all();
        $businesses = Business::all();
        $locations = BusinessLocation::all();
        $products = Product::all();
        return view('inventory_summaries.create', compact('tenants', 'businesses', 'locations', 'products'));
    }

    public function store(Request $request)
    {
        // if (!auth()->user()->hasPermissionTo('create-inventory-summaries')) {
        //     throw UnauthorizedException::forPermissions(['create-inventory-summaries']);
        // }

        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'business_id' => 'required|exists:businesses,id',
            'location_id' => 'required|exists:business_locations,id',
            'product_id' => 'required|exists:products,id',
            'adjustment_type' => 'required|in:increase,decrease',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::find($validated['product_id']);
            $inventorySummary = InventorySummary::firstOrCreate(
                [
                    'tenant_id' => $validated['tenant_id'],
                    'business_id' => $validated['business_id'],
                    'location_id' => $validated['location_id'],
                    'product_id' => $validated['product_id'],
                ],
                [
                    'stock_quantity' => $product->stock_quantity,
                    'last_updated' => now(),
                ]
            );

            $newStockQuantity = $inventorySummary->stock_quantity;
            if ($validated['adjustment_type'] === 'increase') {
                $newStockQuantity += $validated['quantity'];
            } else {
                $newStockQuantity -= $validated['quantity'];
                if ($newStockQuantity < 0) {
                    throw new \Exception("Cannot decrease stock below 0 for product {$product->name}. Current stock: {$inventorySummary->stock_quantity}.");
                }
            }

            // Update InventorySummary
            $inventorySummary->update([
                'stock_quantity' => $newStockQuantity,
                'last_updated' => now(),
            ]);

            // Sync Product stock_quantity
            $product->update(['stock_quantity' => $newStockQuantity]);

            // Log adjustment
            InventoryAdjustment::create([
                'inventory_summary_id' => $inventorySummary->id,
                'product_id' => $validated['product_id'],
                'adjustment_type' => $validated['adjustment_type'],
                'quantity' => $validated['quantity'],
                'reason' => $validated['reason'],
                'adjusted_at' => now(),
            ]);

            DB::commit();
            return redirect()->route('inventory_summaries.index')->with('success', 'Inventory adjustment completed, and product stock updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(InventorySummary $inventorySummary)
    {
        // if (!auth()->user()->hasPermissionTo('view-inventory-summaries')) {
        //     throw UnauthorizedException::forPermissions(['view-inventory-summaries']);
        // }

        $inventorySummary->load(['tenant', 'business', 'location', 'product']);
        return view('inventory_summaries.show', compact('inventorySummary'));
    }

    public function edit(InventorySummary $inventorySummary)
    {
        // if (!auth()->user()->hasPermissionTo('edit-inventory-summaries')) {
        //     throw UnauthorizedException::forPermissions(['edit-inventory-summaries']);
        // }

        $tenants = Tenant::all();
        $businesses = Business::all();
        $locations = BusinessLocation::all();
        $products = Product::all();
        return view('inventory_summaries.edit', compact('inventorySummary', 'tenants', 'businesses', 'locations', 'products'));
    }

    public function update(Request $request, InventorySummary $inventorySummary)
    {
        // if (!auth()->user()->hasPermissionTo('edit-inventory-summaries')) {
        //     throw UnauthorizedException::forPermissions(['edit-inventory-summaries']);
        // }

        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'business_id' => 'required|exists:businesses,id',
            'location_id' => 'required|exists:business_locations,id',
            'product_id' => 'required|exists:products,id',
            'adjustment_type' => 'required|in:increase,decrease',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $product = Product::find($validated['product_id']);
            $newStockQuantity = $inventorySummary->stock_quantity;

            if ($validated['adjustment_type'] === 'increase') {
                $newStockQuantity += $validated['quantity'];
            } else {
                $newStockQuantity -= $validated['quantity'];
                if ($newStockQuantity < 0) {
                    throw new \Exception("Cannot decrease stock below 0 for product {$product->name}. Current stock: {$inventorySummary->stock_quantity}.");
                }
            }

            // Update InventorySummary
            $inventorySummary->update([
                'tenant_id' => $validated['tenant_id'],
                'business_id' => $validated['business_id'],
                'location_id' => $validated['location_id'],
                'product_id' => $validated['product_id'],
                'stock_quantity' => $newStockQuantity,
                'last_updated' => now(),
            ]);

            // Sync Product stock_quantity
            $product->update(['stock_quantity' => $newStockQuantity]);

            // Log adjustment
            InventoryAdjustment::create([
                'inventory_summary_id' => $inventorySummary->id,
                'product_id' => $validated['product_id'],
                'adjustment_type' => $validated['adjustment_type'],
                'quantity' => $validated['quantity'],
                'reason' => $validated['reason'],
                'adjusted_at' => now(),
            ]);

            DB::commit();
            return redirect()->route('inventory_summaries.index')->with('success', 'Inventory adjustment updated, and product stock updated.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}