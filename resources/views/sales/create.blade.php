@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Create Sale</h1>
        <form action="{{ route('sales.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium">Tenant</label>
                <select name="tenant_id" class="mt-1 block w-full border rounded p-2">
                    @foreach ($tenants as $tenant)
                        <option value="{{ $tenant->id }}">{{ $tenant->name }}</option>
                    @endforeach
                </select>
                @error('tenant_id') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Business</label>
                <select name="business_id" class="mt-1 block w-full border rounded p-2">
                    @foreach ($businesses as $business)
                        <option value="{{ $business->id }}">{{ $business->name }}</option>
                    @endforeach
                </select>
                @error('business_id') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Location</label>
                <select name="location_id" class="mt-1 block w-full border rounded p-2">
                    @foreach ($locations as $location)
                        <option value="{{ $location->id }}">{{ $location->name }}</option>
                    @endforeach
                </select>
                @error('location_id') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Customer</label>
                <select name="customer_id" class="mt-1 block w-full border rounded p-2">
                    <option value="">None</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->first_name }} {{ $customer->last_name }}</option>
                    @endforeach
                </select>
                @error('customer_id') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Employee</label>
                <select name="user_id" class="mt-1 block w-full border rounded p-2">
                    @foreach ($employees as $employee)
                        <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                    @endforeach
                </select>
                @error('user_id') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Status</label>
                <select name="status" class="mt-1 block w-full border rounded p-2">
                    <option value="completed">Completed</option>
                    <option value="pending">Pending</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                @error('status') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <h2 class="text-lg font-semibold">Sale Items</h2>
                <div id="items">
                    <div class="item mb-4 border p-4 rounded">
                        <label class="block text-sm font-medium">Product</label>
                        <select name="items[0][product_id]" class="mt-1 block w-full border rounded p-2">
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->price }})</option>
                            @endforeach
                        </select>
                        <label class="block text-sm font-medium mt-2">Quantity</label>
                        <input type="number" name="items[0][quantity]" min="1" class="mt-1 block w-full border rounded p-2" value="1">
                        <label class="block text-sm font-medium mt-2">Tax Rate</label>
                        <select name="items[0][tax_rate_id]" class="mt-1 block w-full border rounded p-2">
                            <option value="">None</option>
                            @foreach ($taxRates as $taxRate)
                                <option value="{{ $taxRate->id }}">{{ $taxRate->rate }}%</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="button" id="add-item" class="bg-gray-500 text-white px-4 py-2 rounded">Add Item</button>
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create</button>
        </form>
    </div>
    <script>
        let itemIndex = 1;
        document.getElementById('add-item').addEventListener('click', () => {
            const itemsDiv = document.getElementById('items');
            const newItem = document.createElement('div');
            newItem.classList.add('item', 'mb-4', 'border', 'p-4', 'rounded');
            newItem.innerHTML = `
                <label class="block text-sm font-medium">Product</label>
                <select name="items[${itemIndex}][product_id]" class="mt-1 block w-full border rounded p-2">
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }} ({{ $product->price }})</option>
                    @endforeach
                </select>
                <label class="block text-sm font-medium mt-2">Quantity</label>
                <input type="number" name="items[${itemIndex}][quantity]" min="1" class="mt-1 block w-full border rounded p-2" value="1">
                <label class="block text-sm font-medium mt-2">Tax Rate</label>
                <select name="items[${itemIndex}][tax_rate_id]" class="mt-1 block w-full border rounded p-2">
                    <option value="">None</option>
                    @foreach ($taxRates as $taxRate)
                        <option value="{{ $taxRate->id }}">{{ $taxRate->rate }}%</option>
                    @endforeach
                </select>
                <button type="button" class="remove-item bg-red-500 text-white px-2 py-1 rounded mt-2">Remove</button>
            `;
            itemsDiv.appendChild(newItem);
            itemIndex++;
            newItem.querySelector('.remove-item').addEventListener('click', () => newItem.remove());
        });
    </script>
@endsection