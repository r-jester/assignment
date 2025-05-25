<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tenant;
use App\Models\Business;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use http\Client\Response;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(["tenant", "business", "category"])->paginate(
            10
        );
        return view("products.index", compact("products"));
    }

    public function create()
    {
        $tenants = Tenant::all();
        $businesses = Business::all();
        $categories = Category::all();
        return view(
            "products.create",
            compact("tenants", "businesses", "categories")
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            "tenant_id" => "required|exists:tenants,id",
            "business_id" => "required|exists:businesses,id",
            "category_id" => "required|exists:categories,id",
            "name" => "required|string|max:255",
            "description" => "nullable|string",
            "price" => "required|numeric|min:0",
            "stock_quantity" => "required|integer|min:0",
            "sku" => "nullable|string|unique:products",
            "barcode" => "nullable|string|unique:products",
            "image" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);

        if ($request->hasFile("image")) {
            $validated["image"] = $request
                ->file("image")
                ->store("products", "public");
        }

        Product::create($validated);

        return redirect()
            ->route("products.index")
            ->with("success", "Product created successfully.");
    }

    public function show(Product $product)
    {
        $product->load(["tenant", "business", "category"]);
        return view("products.show", compact("product"));
    }

    public function edit(Product $product)
    {
        $tenants = Tenant::all();
        $businesses = Business::all();
        $categories = Category::all();
        return view(
            "products.edit",
            compact("product", "tenants", "businesses", "categories")
        );
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            "tenant_id" => "required|exists:tenants,id",
            "business_id" => "required|exists:businesses,id",
            "category_id" => "required|exists:categories,id",
            "name" => "required|string|max:255",
            "description" => "nullable|string",
            "price" => "required|numeric|min:0",
            "stock_quantity" => "required|integer|min:0",
            "sku" => "nullable|string|unique:products,sku," . $product->id,
            "barcode" =>
                "nullable|string|unique:products,barcode," . $product->id,
            "image" => "nullable|image|mimes:jpeg,png,jpg,gif|max:2048",
        ]);

        if ($request->hasFile("image")) {
            if ($product->image) {
                Storage::disk("public")->delete($product->image);
            }
            $validated["image"] = $request
                ->file("image")
                ->store("products", "public");
        }

        $product->update($validated);

        return redirect()
            ->route("products.index")
            ->with("success", "Product updated successfully.");
    }

    public function search($query): Response
    {
        return Product::where("name", "like", $query)->get();
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk("public")->delete($product->image);
        }
        $product->delete();

        return redirect()
            ->route("products.index")
            ->with("success", "Product deleted successfully.");
    }
}
