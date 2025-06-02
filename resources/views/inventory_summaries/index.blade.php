@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Inventory Summaries</h1>
        <a href="{{ route('inventory_summaries.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Create New Inventory Summary</a>
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Product</th>
                    <th class="py-2 px-4 border-b">Tenant</th>
                    <th class="py-2 px-4 border-b">Business</th>
                    <th class="py-2 px-4 border-b">Location</th>
                    <th class="py-2 px-4 border-b">Stock Quantity</th>
                    <th class="py-2 px-4 border-b">Last Updated</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($inventorySummaries as $inventorySummary)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $inventorySummary->product ? $inventorySummary->product->name : 'N/A' }}</td>
                        <td class="py-2 px-4 border-b">{{ $inventorySummary->tenant ? $inventorySummary->tenant->name : 'N/A' }}</td>
                        <td class="py-2 px-4 border-b">{{ $inventorySummary->business ? $inventorySummary->business->name : 'N/A' }}</td>
                        <td class="py-2 px-4 border-b">{{ $inventorySummary->location ? $inventorySummary->location->name : 'N/A' }}</td>
                        <td class="py-2 px-4 border-b">{{ $inventorySummary->stock_quantity }}</td>
                        <td class="py-2 px-4 border-b">{{ $inventorySummary->last_updated->format('Y-m-d H:i:s') }}</td>
                        <td class="py-2 px-4 border-b">
                            <a href="{{ route('inventory_summaries.show', $inventorySummary) }}" class="text-blue-500 mr-2">View</a>
                            <a href="{{ route('inventory_summaries.edit', $inventorySummary) }}" class="text-green-500">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $inventorySummaries->links() }}
    </div>
@endsection