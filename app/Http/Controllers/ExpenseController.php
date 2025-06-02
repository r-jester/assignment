<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Tenant;
use App\Models\Business;
use App\Models\BusinessLocation;
use App\Models\Employee;
use App\Models\Category;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        // $expenses = Expense::with(['tenant', 'business', 'location', 'user', 'category'])->paginate(10);
        $expenses = Expense::with(['user', 'category'])->paginate(10);
        return view('expenses.index', compact('expenses'));
    }

    public function create()
    {
        // $tenants = Tenant::all();
        // $businesses = Business::all();
        // $locations = BusinessLocation::all();
        $employees = Employee::all();
        $categories = Category::all();
        return view('expenses.create', compact('employees', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // 'tenant_id' => 'required|exists:tenants,id',
            // 'business_id' => 'required|exists:businesses,id',
            // 'location_id' => 'required|exists:business_locations,id',
            'user_id' => 'required|exists:employees,id',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'expense_date' => 'required|date',
        ]);

        Expense::create($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense created successfully.');
    }

    public function show(Expense $expense)
    {
        $expense->load(['user', 'category']);
        return view('expenses.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        // $tenants = Tenant::all();
        // $businesses = Business::all();
        // $locations = BusinessLocation::all();
        $employees = Employee::all();
        $categories = Category::all();
        return view('expenses.edit', compact('expense', 'employees', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            // 'tenant_id' => 'required|exists:tenants,id',
            // 'business_id' => 'required|exists:businesses,id',
            // 'location_id' => 'required|exists:business_locations,id',
            'user_id' => 'required|exists:employees,id',
            'category_id' => 'required|exists:categories,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'expense_date' => 'required|date',
        ]);

        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()->route('expenses.index')->with('success', 'Expense deleted successfully.');
    }
}