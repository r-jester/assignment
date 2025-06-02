<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Tenant;
use App\Models\Business;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with(['tenant', 'business'])->paginate(10);
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        $tenants = Tenant::all();
        $businesses = Business::all();
        return view('categories.create', compact('tenants', 'businesses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'business_id' => 'required|exists:businesses,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        $category->load(['tenant', 'business']);
        return view('categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        $tenants = Tenant::all();
        $businesses = Business::all();
        return view('categories.edit', compact('category', 'tenants', 'businesses'));
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'business_id' => 'required|exists:businesses,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}