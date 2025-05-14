@extends('layouts.master')

@section('title', 'Home')

@push('styles')
    <style>
        .dashboard-header {
            background: linear-gradient(135deg, #1e3a8a, #3b82f6);
            color: white;
            padding: 2rem;
            border-radius: 0.5rem;
            margin-bottom: 2rem;
            text-align: center;
        }

        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
        }

        .google-map {
            margin-top: 2rem;
            height: 400px;
            width: 100%;
            border-radius: 0.5rem;
            overflow: hidden;
        }
    </style>
@endpush

@section('content')
    <div class="container dashboard-content">
        <!-- Welcome Header -->
        <div class="dashboard-header">
            <h1 class="text-3xl font-bold">Welcome {{ session()->get('NAME', 'Guest') }}</h1>
            {{-- <a href="/logout" class="btn btn-success mt-3 text-sm">Log Out</a> --}}
        </div>

        <!-- Cards Section -->
        <div class="cards grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="card card-hover p-4">
                <h2 class="text-lg font-semibold">Users</h2>
                <p class="mt-2 text-gray-600">1,245</p>
                <button class="btn btn-primary mt-3 text-sm">Details</button>
            </div>
            <div class="card card-hover p-4">
                <h2 class="text-lg font-semibold">Revenue</h2>
                <p class="mt-2 text-gray-600">$12,345</p>
                <button class="btn btn-success mt-3 text-sm">Report</button>
            </div>
            <div class="card card-hover p-4">
                <h2 class="text-lg font-semibold">Tasks</h2>
                <p class="mt-2 text-gray-600">8</p>
                <button class="btn btn-warning mt-3 text-sm">Manage</button>
            </div>
            <div class="card card-hover p-4">
                <h2 class="text-lg font-semibold">Notifications</h2>
                <p class="mt-2 text-gray-600">3</p>
                <button class="btn btn-info mt-3 text-sm">Check</button>
            </div>
            <div class="card card-hover p-4">
                <h2 class="text-lg font-semibold">Orders</h2>
                <p class="mt-2 text-gray-600">45</p>
                <button class="btn btn-primary mt-3 text-sm">View</button>
            </div>
            <div class="card card-hover p-4">
                <h2 class="text-lg font-semibold">Support Tickets</h2>
                <p class="mt-2 text-gray-600">12</p>
                <button class="btn btn-success mt-3 text-sm">Resolve</button>
            </div>
            <div class="card card-hover p-4">
                <h2 class="text-lg font-semibold">Projects</h2>
                <p class="mt-2 text-gray-600">6</p>
                <button class="btn btn-warning mt-3 text-sm">Track</button>
            </div>
            <div class="card card-hover p-4">
                <h2 class="text-lg font-semibold">Feedback</h2>
                <p class="mt-2 text-gray-600">9</p>
                <button class="btn btn-info mt-3 text-sm">Read</button>
            </div>
        </div>

        <!-- Google Map Section -->
        <div class="google-map">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3151.835434509364!2d144.9537353153169!3d-37.81627997975166!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642af0f11fd81%3A0xf577d9f8a6f5b6e8!2sMelbourne%20VIC%2C%20Australia!5e0!3m2!1sen!2sus!4v1698765432100!5m2!1sen!2sus"
                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('button.btn').click(function() {
                alert('Button clicked: ' + $(this).text());
            });
        });
    </script>
@endpush
