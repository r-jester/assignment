@extends('layouts.app')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-4">Expenses</h1>
        <a href="{{ route('expenses.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Create Expense</a>
        <table class="min-w-full bg-white border">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Category</th>
                    <th class="py-2 px-4 border-b">Amount</th>
                    <th class="py-2 px-4 border-b">Date</th>
                    <th class="py-2 px-4 border-b">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($expenses as $expense)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $expense->category->name }}</td>
                        <td class="py-2 px-4 border-b">{{ $expense->amount }}</td>
                        <td class="py-2 px-4 border-b">{{ $expense->expense_date->format('Y-m-d') }}</td>
                        <td class="py-2 px-4 border-b">
                            <a href="{{ route('expenses.show', $expense) }}" class="text-blue-500">View</a>
                            <a href="{{ route('expenses.edit', $expense) }}" class="text-green-500 ml-2">Edit</a>
                            <form action="{{ route('expenses.destroy', $expense) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 ml-2" onclick="return confirm('Are you sure?')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $expenses->links() }}
    </div>
@endsection