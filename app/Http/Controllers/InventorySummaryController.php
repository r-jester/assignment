<?php

namespace App\Http\Controllers;

use App\Models\InventorySummary;
use App\Models\Tenant;
use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\Product;
use Illuminate\Http\Request;

class InventorySummaryController extends Controller
{
    public function index()
    {
        $inventorySummaries = InventorySummary::with(['tenant', 'business', 'location', 'product'])->paginate(10);
        return view('inventory_summaries.index', compact('inventorySummaries'));
    }

    public function show(InventorySummary $inventorySummary)
    {
        $inventorySummary->load(['tenant', 'business', 'location', 'product']);
        return view('inventory_summaries.show', compact('inventorySummary'));
    }
}