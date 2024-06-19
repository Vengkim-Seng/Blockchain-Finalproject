@extends('layout.dashboard-parent-landlord')

@section('content')
    @parent <!-- Retain master layout content -->
    <main class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200 p-4 sm:p-6 lg:p-8">
        
       <!-- Check for success message -->
        @if (session('success'))
        <div id="flash-message" class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <!-- Pending Payments -->
        <div class="container">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary">
                    <h2 class="mb-0 text-white"><i class="fas fa-hourglass-half text-white"></i> Pending Rent Payments</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">Room Number</th>
                                    <th class="text-center">Tenant Name</th>
                                    <th class="text-center">Rent Due Date</th>
                                    <th class="text-center">Amount ($)</th>
                                    <th class="text-center">Proof of Payment</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pendingPayments as $payment)
                                    <tr>
                                        <td class="text-center">{{ $payment->lease->room_number }}</td>
                                        <td class="text-center">{{ $payment->tenant->tenant_name }}</td>
                                        <td class="text-center">{{ $payment->payment_date }}</td>
                                        <td class="text-center">{{ $payment->amount }}</td>
                                        <td class="text-center">
                                            @if ($payment->proof_of_payment)
                                                <a href="{{ asset('storage/' . $payment->proof_of_payment) }}" target="_blank">View Proof</a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('rent.updateStatus', $payment) }}" method="POST">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" class="form-control" required>
                                                    <option value="pending" {{ $payment->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="approved" {{ $payment->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                                    <option value="declined" {{ $payment->status == 'declined' ? 'selected' : '' }}>Declined</option>
                                                </select>
                                                <button type="submit" class="btn btn-primary btn-sm mt-2" style="white-space: nowrap;"><i class="fas fa-check"></i> Update Status</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">No pending payments found!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Approved Payments -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary">
                    <h2 class="mb-0 text-white"><i class="fas fa-check-circle text-white"></i> Approved Rent Payments</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">Room Number</th>
                                    <th class="text-center">Tenant Name</th>
                                    <th class="text-center">Rent Due Date</th>
                                    <th class="text-center">Amount ($)</th>
                                    <th class="text-center">Proof of Payment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($approvedPayments as $payment)
                                    <tr>
                                        <td class="text-center">{{ $payment->lease->room_number }}</td>
                                        <td class="text-center">{{ $payment->tenant->tenant_name }}</td>
                                        <td class="text-center">{{ $payment->payment_date }}</td>
                                        <td class="text-center">{{ $payment->amount }}</td>
                                        <td class="text-center">
                                            @if ($payment->proof_of_payment)
                                                <a href="{{ asset('storage/' . $payment->proof_of_payment) }}" target="_blank">View Proof</a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No approved payments found!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Declined Payments -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-primary">
                    <h2 class="mb-0 text-white"><i class="fas fa-times-circle text-white"></i> Declined Rent Payments</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">Room Number</th>
                                    <th class="text-center">Tenant Name</th>
                                    <th class="text-center">Rent Due Date</th>
                                    <th class="text-center">Amount ($)</th>
                                    <th class="text-center">Proof of Payment</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($declinedPayments as $payment)
                                    <tr>
                                        <td class="text-center">{{ $payment->lease->room_number }}</td>
                                        <td class="text-center">{{ $payment->tenant->tenant_name }}</td>
                                        <td class="text-center">{{ $payment->payment_date }}</td>
                                        <td class="text-center">{{ $payment->amount }}</td>
                                        <td class="text-center">
                                            @if ($payment->proof_of_payment)
                                                <a href="{{ asset('storage/' . $payment->proof_of_payment) }}" target="_blank">View Proof</a>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">No declined payments found!</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>




    </main>
@endsection
