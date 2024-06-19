@extends('layout.dashboard-parent-landlord')

@section('content')
    @parent <!-- Retain master layout content -->
        <main class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200 p-4 sm:p-6 lg:p-8">
            <div class="max-w-3xl mx-auto bg-white rounded-lg shadow-md p-6 lg:p-8">
                <h2 class="text-2xl font-semibold text-gray-800 mb-6">Lease Details</h2>
                <div class="mb-4">
                    <p class="font-semibold">Tenant Name:</p>
                    <p>{{ $lease->tenant->tenant_name }}</p>
                </div>
                <div class="mb-4">
                    <p class="font-semibold">Room Number:</p>
                    <p>{{ $lease->room_number }}</p>
                </div>
                <div class="mb-4">
                    <p class="font-semibold">Start Date:</p>
                    <p>{{ $lease->start_date }}</p>
                </div>
                <div class="mb-4">
                    <p class="font-semibold">End Date:</p>
                    <p>{{ $lease->end_date }}</p>
                </div>
                <div class="mb-4">
                    <p class="font-semibold">Lease Agreement:</p>
                    <a href="{{ asset('storage/' . $lease->lease_agreement) }}" target="_blank">View Lease Agreement</a>
                </div>
            </div>
        </main>
@endsection

