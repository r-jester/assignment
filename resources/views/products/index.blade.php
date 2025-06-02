@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Products</h1>
        <a href="{{ route('products.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Create Product</a>
        <input type="text" placeholder="text" onkeydown="j(this)">
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Name</th>
                    <th class="py-2 px-4 border-b">Tenant</th>
                    <th class="py-2 px-4 border-b">Business</th>
                    <th class="py-2 px-4 border-b">Category</th>
                    <th class="py-2 px-4 border-b">Price</th>
                    <th class="py-2 px-4 border-b">Stock</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody id="jester">
                @foreach ($products as $product)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $product->name }}</td>
                        <td class="py-2 px-4 border-b">{{ $product->tenant->name }}</td>
                        <td class="py-2 px-4 border-b">{{ $product->business->name }}</td>
                        <td class="py-2 px-4 border-b">{{ $product->category->name }}</td>
                        <td class="py-2 px-4 border-b">{{ $product->price }}</td>
                        <td class="py-2 px-4 border-b">{{ $product->stock_quantity }}</td>
                        <td class="py-2 px-4 border-b">
                            <a href="{{ route('products.show', $product) }}" class="text-blue-500">View</a>
                            <a href="{{ route('products.edit', $product) }}" class="text-green-500 ml-2">Edit</a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $products->links() }}
    </div>
@endsection

@push("scripts")
    <script>
    function j(abc) {
        fetch(`http://127.0.0.1:8000/api/product/search/${abc.value}`)
            .then(response => response.json())
            .then(data => {
                console.log(data);
                let tbody = document.getElementById("jester");
                tbody.innerHTML = ""; // Clear existing rows

                data.data.forEach(product => {
                    let row = `<tr>
                        <td class="py-2 px-4 border-b">${product.name}</td>
                        <td class="py-2 px-4 border-b">${product.tenant.name}</td>
                        <td class="py-2 px-4 border-b">${product.business.name}</td>
                        <td class="py-2 px-4 border-b">${product.category.name}</td>
                        <td class="py-2 px-4 border-b">${product.price}</td>
                        <td class="py-2 px-4 border-b">${product.stock_quantity}</td>
                        <td class="py-2 px-4 border-b">
                            <a href="/products/${product.id}" class="text-blue-500">View</a>
                            <a href="/products/${product.id}/edit" class="text-green-500 ml-2">Edit</a>
                            <form action="/products/${product.id}" method="POST" class="inline">
                                <button type="submit" class="text-red-500 ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>`;
                    tbody.innerHTML += row;
                });
            })
            .catch(error => console.error('Error fetching data:', error));
        }
    </script>
@endpush
