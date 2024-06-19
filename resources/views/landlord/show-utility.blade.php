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

        <!-- Pending Utility Bills -->
        <div class="container ">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary">
                <h2 class="mb-0 text-white"><i class="fas fa-hourglass-half text-white"></i> Pending Utility Bills</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-center">Room Number</th>
                                <th class="text-center">Tenant Name</th>
                                <th class="text-center">Billing Date</th>
                                <th class="text-center">Total Amount ($)</th>
                                <th class="text-center">Proof of Meter Readings</th>
                                <th class="text-center">Proof of Payment</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($pendingPayments as $payment)
                                <tr>
                                    <td class="text-center">{{ $payment->lease->room_number }}</td>
                                    <td class="text-center">{{ $payment->tenant->tenant_name }}</td>
                                    <td class="text-center">{{ $payment->billing_date }}</td>
                                    <td class="text-center">{{ $payment->total_amount }}</td>
                                    <td class="text-center">
                                        @if ($payment->proof_of_meter_reading)
                                            @foreach (json_decode($payment->proof_of_meter_reading) as $proof)
                                                <a href="{{ asset('storage/' . $proof) }}" target="_blank">View</a><br>
                                            @endforeach
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($payment->proof_of_utility_payment)
                                            <a href="{{ asset('storage/' . $payment->proof_of_utility_payment) }}" target="_blank">View Proof</a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('utility.updateStatus', $payment) }}" method="POST">
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
                                    <td colspan="7" class="text-center text-muted">No pending utility bills found!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Approved Utility Bills -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary">
                <h2 class="mb-0 text-white"><i class="fas fa-check-circle text-white"></i> Approved Utility Bills</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-center">Room Number</th>
                                <th class="text-center">Tenant Name</th>
                                <th class="text-center">Billing Date</th>
                                <th class="text-center">Total Amount ($)</th>
                                <th class="text-center">Proof of Meter Readings</th>
                                <th class="text-center">Proof of Payment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($approvedPayments as $payment)
                                <tr>
                                    <td class="text-center">{{ $payment->lease->room_number }}</td>
                                    <td class="text-center">{{ $payment->tenant->tenant_name }}</td>
                                    <td class="text-center">{{ $payment->billing_date }}</td>
                                    <td class="text-center">{{ $payment->total_amount }}</td>
                                    <td class="text-center">
                                        @if ($payment->proof_of_meter_reading)
                                            @foreach (json_decode($payment->proof_of_meter_reading) as $proof)
                                                <a href="{{ asset('storage/' . $proof) }}" target="_blank">View</a><br>
                                            @endforeach
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($payment->proof_of_utility_payment)
                                            <a href="{{ asset('storage/' . $payment->proof_of_utility_payment) }}" target="_blank">View Proof</a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No approved utility bills found!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Declined Utility Bills -->
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary">
                <h2 class="mb-0 text-white"><i class="fas fa-times-circle text-white"></i> Declined Utility Bills</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-center">Room Number</th>
                                <th class="text-center">Tenant Name</th>
                                <th class="text-center">Billing Date</th>
                                <th class="text-center">Total Amount ($)</th>
                                <th class="text-center">Proof of Meter Readings</th>
                                <th class="text-center">Proof of Payment</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($declinedPayments as $payment)
                                <tr>
                                    <td class="text-center">{{ $payment->lease->room_number }}</td>
                                    <td class="text-center">{{ $payment->tenant->tenant_name }}</td>
                                    <td class="text-center">{{ $payment->billing_date }}</td>
                                    <td class="text-center">{{ $payment->total_amount }}</td>
                                    <td class="text-center">
                                        @if ($payment->proof_of_meter_reading)
                                            @foreach (json_decode($payment->proof_of_meter_reading) as $proof)
                                                <a href="{{ asset('storage/' . $proof) }}" target="_blank">View</a><br>
                                            @endforeach
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @if ($payment->proof_of_utility_payment)
                                            <a href="{{ asset('storage/' . $payment->proof_of_utility_payment) }}" target="_blank">View Proof</a>
                                        @else
                                            N/A
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No declined utility bills found!</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>



        <script>
            // Hide the flash message after 2 seconds
            setTimeout(function() {
                var flashMessage = document.getElementById('flash-message');
                if (flashMessage) {
                    flashMessage.style.display = 'none';
                }
            }, 2000); // 2000 milliseconds = 2 seconds
        </script>
    </main>
@endsection

