@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Businesses</h1>
        <a href="{{ route('businesses.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Create Business</a>
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Name</th>
                    <th class="py-2 px-4 border-b">Tenant</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($businesses as $business)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $business->name }}</td>
                        <td class="py-2 px-4 border-b">{{ $business->tenant->name }}</td>
                        <td class="py-2 px-4 border-b">
                            <a href="{{ route('businesses.show', $business) }}" class="text-blue-500">View</a>
                            <a href="{{ route('businesses.edit', $business) }}" class="text-green-500 ml-2">Edit</a>
                            <form action="{{ route('businesses.destroy', $business) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $businesses->links() }}
    </div>
@endsection