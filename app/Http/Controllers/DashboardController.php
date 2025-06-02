<?php

namespace App\Http\Controllers;

use App\Models\SalesSummary;
use App\Models\InventorySummary;
use App\Models\Purchase;
use App\Models\Expense;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalSales = SalesSummary::sum('total_sales');
        $totalExpenses = Expense::sum('amount');
        $lowStockProducts = InventorySummary::where('stock_quantity', '<', 10)->count();
        $recentPurchases = Purchase::with(['supplier'])->latest()->take(5)->get();

        return view('dashboard', compact('totalSales', 'totalExpenses', 'lowStockProducts', 'recentPurchases'));
    }
}