<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductApi extends Controller
{
    public function search($query): JsonResponse
    {
        return response()->json(Product::where("name", "like", $query)->get());
    }
}
