<?php

namespace App\Http\Controllers;

use App\Models\SalesSummary;
use App\Models\Tenant;
use App\Models\Business;
use App\Models\BusinessLocation;
use Illuminate\Http\Request;

class SalesSummaryController extends Controller
{
    public function index()
    {
        $salesSummaries = SalesSummary::with(['tenant', 'business', 'location'])->paginate(10);
        return view('sales_summaries.index', compact('salesSummaries'));
    }

    public function show(SalesSummary $salesSummary)
    {
        $salesSummary->load(['tenant', 'business', 'location']);
        return view('sales_summaries.show', compact('salesSummary'));
    }
}