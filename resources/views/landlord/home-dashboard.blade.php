@extends('layout.dashboard-parent-landlord')

@section('content')
    @parent <!-- Retain master layout content -->

    <!-- Main Content Area -->
    <main class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200 p-4 sm:p-6 lg:p-8">
        <!-- Summary Cards -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-users text-primary"></i> Total Tenants</h5>
                        <p class="card-text display-4">{{ $totalTenants }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-file-signature text-primary"></i> Active Leases</h5>
                        <p class="card-text display-4">{{ $activeLeases }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-money-bill-wave text-primary"></i> Pending Rent</h5>
                        <p class="card-text display-4">{{ $pendingRentPayments }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><i class="fas fa-file-invoice-dollar text-primary"></i> Pending Utility</h5>
                        <p class="card-text display-4">{{ $pendingUtilityPayments }}</p>
                    </div>
                </div>
            </div>
        </div>
    
        <!-- Quick Actions -->
        <div class="d-flex justify-content-between mb-4">
            <a href="{{ route('tenant.register') }}" class="btn btn-primary"><i class="fas fa-user-plus"></i> Register New Tenant</a>
            <a href="{{ route('leases.create') }}" class="btn btn-primary"><i class="fas fa-file-contract"></i> Create New Lease</a>
            <a href="{{ route('rent.create') }}" class="btn btn-primary"><i class="fas fa-money-check-alt"></i> Add New Rent Entry</a>
            <a href="{{ route('utility.create') }}" class="btn btn-primary"><i class="fas fa-file-invoice"></i> Add New Utility Bill</a>
        </div>
    
        <!-- Summary Tables -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between">
                <span><i class="fas fa-calendar-alt"></i> Upcoming Lease Expirations</span>
                <a href="{{ route('leases.index') }}" class="btn btn-light"><i class="fas fa-arrow-right"></i> Go to Lease</a>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Tenant Name</th>
                                <th>Room Number</th>
                                <th>End Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($upcomingLeaseExpirations as $lease)
                                <tr>
                                    <td>{{ $lease->tenant->tenant_name }}</td>
                                    <td>{{ $lease->room_number }}</td>
                                    <td>{{ $lease->end_date }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No upcoming lease expirations found!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <div class="card mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between">
                <span><i class="fas fa-money-bill-wave"></i> Pending Rent Payments</span>
                {{-- @if ($pendingRentPayments > 0) --}}
                    <a href="{{ route('rent.index') }}" class="btn btn-light"><i class="fas fa-arrow-right"></i> Go to Rent</a>
                {{-- @endif --}}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Tenant Name</th>
                                <th>Room Number</th>
                                <th>Rent Due Date</th>
                                <th>Amount ($)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentPendingRentPayments as $payment)
                                <tr>
                                    <td>{{ $payment->tenant->tenant_name }}</td>
                                    <td>{{ $payment->lease->room_number }}</td>
                                    <td>{{ $payment->payment_date }}</td>
                                    <td>{{ $payment->amount }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No pending rent payments found!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between">
                <span><i class="fas fa-file-invoice-dollar"></i> Pending Utility Bills</span>
                {{-- @if ($pendingUtilityPayments > 0) --}}
                    <a href="{{ route('utility.index') }}" class="btn btn-light"><i class="fas fa-arrow-right"></i> Go to Utility</a>
                {{-- @endif --}}
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>Tenant Name</th>
                                <th>Room Number</th>
                                <th>Billing Date</th>
                                <th>Amount ($)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($recentPendingUtilityPayments as $payment)
                                <tr>
                                    <td>{{ $payment->tenant->tenant_name }}</td>
                                    <td>{{ $payment->lease->room_number }}</td>
                                    <td>{{ $payment->billing_date }}</td>
                                    <td>{{ $payment->total_amount }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">No pending utility payments found!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        
    </main>
    <!-- End Main Content Area -->
@endsection
