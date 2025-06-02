@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Edit Payment Method</h1>
        <form action="{{ route('payment_methods.update', $paymentMethod) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium">Tenant</label>
                <select name="tenant_id" class="mt-1 block w-full border rounded p-2">
                    <option value="" {{ is_null($paymentMethod->tenant_id) ? 'selected' : '' }}>None</option>
                    @foreach ($tenants as $tenant)
                        <option value="{{ $tenant->id }}" {{ $paymentMethod->tenant_id == $tenant->id ? 'selected' : '' }}>{{ $tenant->name }}</option>
                    @endforeach
                </select>
                @error('tenant_id') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Business</label>
                <select name="business_id" class="mt-1 block w-full border rounded p-2">
                    @foreach ($businesses as $business)
                        <option value="{{ $business->id }}" {{ $paymentMethod->business_id == $business->id ? 'selected' : '' }}>{{ $business->name }}</option>
                    @endforeach
                </select>
                @error('business_id') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Name</label>
                <input type="text" name="name" class="mt-1 block w-full border rounded p-2" value="{{ $paymentMethod->name }}">
                @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Active</label>
                <input type="checkbox" name="is_active" value="1" {{ $paymentMethod->is_active ? 'checked' : '' }} class="mt-1">
                @error('is_active') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
        </form>
    </div>
@endsection