@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Inventory Summaries</h1>
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Product</th>
                    <th class="py-2 px-4 border-b">Tenant</th>
                    <th class="py-2 px-4 border-b">Business</th>
                    <th class="py-2 px-4 border-b">Location</th>
                    <th class="py-2 px-4 border-b">Stock Quantity</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inventorySummaries as $inventorySummary)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $inventorySummary->product->name }}</td>
                        <td class="py-2 px-4 border-b">{{ $inventorySummary->tenant->name }}</td>
                        <td class="py-2 px-4 border-b">{{ $inventorySummary->business->name }}</td>
                        <td class="py-2 px-4 border-b">{{ $inventorySummary->location->name }}</td>
                        <td class="py-2 px-4 border-b">{{ $inventorySummary->stock_quantity }}</td>
                        <td class="py-2 px-4 border-b">
                            <a href="{{ route('inventory_summaries.show', $inventorySummary) }}" class="text-blue-500">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $inventorySummaries->links() }}
    </div>
@endsection