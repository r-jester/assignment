@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Tenants</h1>
        <a href="{{ route('tenants.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Create Tenant</a>
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Name</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($tenants as $tenant)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $tenant->name }}</td>
                        <td class="py-2 px-4 border-b">
                            <a href="{{ route('tenants.show', $tenant) }}" class="text-blue-500">View</a>
                            <a href="{{ route('tenants.edit', $tenant) }}" class="text-green-500 ml-2">Edit</a>
                            <form action="{{ route('tenants.destroy', $tenant) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $tenants->links() }}
    </div>
@endsection