@extends('layout.dashboard-parent-tenant')

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

            <!-- Your Lease Agreement -->
            <div class="container">
            <div class="card shadow-sm">
                <div class="card-header bg-primary">
                    <h2 class="mb-0 text-white"><i class="fas fa-file-signature text-white"></i> Your Lease Agreement</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">Your Landlord</th>
                                    <th class="text-center">Your Room Number</th>
                                    <th class="text-center">Start Date</th>
                                    <th class="text-center">End Date</th>
                                    <th class="text-center">Lease Agreement</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($leases as $lease)
                                    <tr>
                                        <td class="text-center">{{ $lease->landlord->landlord_name }}</td>
                                        <td class="text-center">{{ $lease->room_number }}</td>
                                        <td class="text-center">{{ $lease->start_date }}</td>
                                        <td class="text-center">{{ $lease->end_date }}</td>
                                        <td class="text-center">
                                            <a href="{{ Storage::url($lease->lease_agreement) }}" target="_blank"><i class="fas fa-file-alt"></i> View Lease Agreement</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">You have no lease agreements!</td>
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
