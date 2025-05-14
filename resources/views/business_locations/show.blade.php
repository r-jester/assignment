@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Business Location Details</h1>
        <div class="bg-white p-6 rounded shadow">
            <p><strong>Tenant:</strong> {{ $businessLocation->tenant->name }}</p>
            <p><strong>Business:</strong> {{ $businessLocation->business->name }}</p>
            <p><strong>Name:</strong> {{ $businessLocation->name }}</p>
            <p><strong>Address:</strong> {{ $businessLocation->address }}</p>
            <div class="mt-4">
                <a href="{{ route('business_locations.edit', $businessLocation) }}" class="bg-green-500 text-white px-4 py-2 rounded">Edit</a>
                <form action="{{ route('business_locations.destroy', $businessLocation) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                </form>
            </div>
        </div>
    </div>
@endsection