<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProductApi;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get("/product/search/{request}", [ProductApi::class, "search"]);
Route::get("/users", [UserController::class, "index"]);
Route::post("/users/create", [UserController::class, "create"]);
Route::put("/users/edit/{id}", [UserController::class, "edit"]);
Route::delete("/users/delete/{id}", [UserController::class, "destroy"]);
