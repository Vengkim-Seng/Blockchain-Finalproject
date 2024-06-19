@extends('layout.dashboard-parent-tenant')

@section('content')
    @parent <!-- Retain master layout content -->

    <!-- Main Content Area -->
    <main class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200 p-4 sm:p-6 lg:p-8">

      <!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h5 class="card-title"><i class="fas fa-file-signature text-primary"></i> Current Lease Details</h5>
                @if ($currentLease)
                    <p class="card-text">Room Number: {{ $currentLease->room_number }}</p>
                    <p class="card-text">Lease Start Date: {{ $currentLease->start_date }}</p>
                    <p class="card-text">Lease End Date: {{ $currentLease->end_date }}</p>
                    @if ($upcomingLeaseExpiration)
                        <div class="alert alert-warning" role="alert">
                            <i class="fas fa-exclamation-triangle"></i> Your lease is expiring soon on {{ $currentLease->end_date }}. Please contact your landlord for renewal.
                        </div>
                    @endif
                    <a href="{{ route('tenant.leases') }}" class="btn btn-primary" style="white-space: nowrap;"><i class="fas fa-arrow-right"></i> Go to Lease</a>
                @else
                    <p class="card-text">No current lease details available.</p>
                @endif
            </div>
        </div>
    </div>
    @if ($declinedRentPayments->count() > 0)
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-exclamation-circle text-primary"></i> Declined Rent Payments</h5>
                    <p class="card-text display-4">{{ $declinedRentPayments->count() }}</p>
                    <a href="{{ route('tenant.showRent') }}" class="btn btn-warning" style="white-space: nowrap;"><i class="fas fa-upload"></i> Re-upload Proof of Payment</a>
                </div>
            </div>
        </div>
    @else
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-money-bill-wave text-primary"></i> Pending Rent Payments</h5>
                    <p class="card-text display-4">{{ $pendingRentPayments->count() }}</p>
                </div>
            </div>
        </div>
    @endif
    @if ($declinedUtilityPayments->count() > 0)
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-exclamation-circle text-primary"></i> Declined Utility Bills</h5>
                    <p class="card-text display-4">{{ $declinedUtilityPayments->count() }}</p>
                    <a href="{{ route('tenant.showUtility') }}" class="btn btn-warning" style="white-space: nowrap;"><i class="fas fa-upload"></i> Re-upload Proof of Payment</a>
                </div>
            </div>
        </div>
    @else
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-file-invoice-dollar text-primary"></i> Pending Utility Bills</h5>
                    <p class="card-text display-4">{{ $pendingUtilityPayments->count() }}</p>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Summary Table -->
@if ($pendingRentPayments->count() > 0)
    <div class="card mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between">
            <span><i class="fas fa-money-bill-wave"></i> Pending Rent Payments</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">Rent Due Date</th>
                            <th class="text-center">Amount ($)</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pendingRentPayments as $payment)
                            <tr>
                                <td class="text-center">{{ $payment->payment_date }}</td>
                                <td class="text-center">{{ $payment->amount }}</td>
                                <td class="text-center">{{ ucfirst($payment->status) }}</td>
                                <td class="text-center">
                                    @if (is_null($payment->proof_of_payment))
                                        <a href="{{ route('tenant.showRent') }}" class="btn btn-primary"><i class="fas fa-upload"></i> Upload Proof of Payment</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No pending payments found!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif

@if ($pendingUtilityPayments->count() > 0)
    <div class="card mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between">
            <span><i class="fas fa-file-invoice-dollar"></i> Pending Utility Bills</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center">Billing Date</th>
                            <th class="text-center">Amount ($)</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pendingUtilityPayments as $payment)
                            <tr>
                                <td class="text-center">{{ $payment->billing_date }}</td>
                                <td class="text-center">{{ $payment->total_amount }}</td>
                                <td class="text-center">{{ ucfirst($payment->status) }}</td>
                                <td class="text-center">
                                    @if (is_null($payment->proof_of_payment))
                                        <a href="{{ route('tenant.showUtility') }}" class="btn btn-primary"><i class="fas fa-upload"></i> Upload Proof of Payment</a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">No pending utility bills found!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endif



    </main>
    <!-- End Main Content Area -->


@endsection

