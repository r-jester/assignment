@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Tenant Details</h1>
        <div class="bg-white p-6 rounded shadow">
            <p><strong>Name:</strong> {{ $tenant->name }}</p>
            <p><strong>Description:</strong> {{ $tenant->description ?? 'N/A' }}</p>
            <div class="mt-4">
                <a href="{{ route('tenants.edit', $tenant) }}" class="bg-green-500 text-white px-4 py-2 rounded">Edit</a>
                <form action="{{ route('tenants.destroy', $tenant) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        </div>
    </div>
@endsection