@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Edit Business</h1>
        <form action="{{ route('businesses.update', $business) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label class="block text-sm font-medium">Tenant</label>
                <select name="tenant_id" class="mt-1 block w-full border rounded p-2">
                    @foreach ($tenants as $tenant)
                        <option value="{{ $tenant->id }}" {{ $business->tenant_id == $tenant->id ? 'selected' : '' }}>{{ $tenant->name }}</option>
                    @endforeach
                </select>
                @error('tenant_id') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Name</label>
                <input type="text" name="name" class="mt-1 block w-full border rounded p-2" value="{{ $business->name }}">
                @error('name') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium">Description</label>
                <textarea name="description" class="mt-1 block w-full border rounded p-2">{{ $business->description }}</textarea>
                @error('description') <span class="text-red-500">{{ $message }}</span> @enderror
            </div>
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
        </form>
    </div>
@endsection