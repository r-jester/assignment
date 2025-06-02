@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Product Details</h1>
        <div class="bg-white p-6 rounded shadow">
            <p><strong>Tenant:</strong> {{ $product->tenant->name }}</p>
            <p><strong>Business:</strong> {{ $product->business->name }}</p>
            <p><strong>Category:</strong> {{ $product->category->name }}</p>
            <p><strong>Name:</strong> {{ $product->name }}</p>
            <p><strong>Description:</strong> {{ $product->description ?? 'N/A' }}</p>
            <p><strong>Price:</strong> {{ $product->price }}</p>
            <p><strong>Stock Quantity:</strong> {{ $product->stock_quantity }}</p>
            <p><strong>SKU:</strong> {{ $product->sku ?? 'N/A' }}</p>
            <p><strong>Barcode:</strong> {{ $product->barcode ?? 'N/A' }}</p>
            @if ($product->image)
                <p><strong>Image:</strong></p>
                <img src="{{ Storage::url($product->image) }}" alt="{{ $product->name }}" class="w-32 h-32 object-cover">
            @endif
            <div class="mt-4">
                <a href="{{ route('products.edit', $product) }}" class="bg-green-500 text-white px-4 py-2 rounded">Edit</a>
                <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        </div>
    </div>
@endsection