@extends('layout.dashboard-parent-landlord')

@section('content')
@parent <!-- Retain master layout content -->
<main
    class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200 p-4 sm:p-6 lg:p-8">

    <!-- Check for success message -->
    @if (session('success'))
        <div id="flash-message" class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Leases Table -->
    <div class="container">
        <div class="card shadow-sm">
            <div class="card-header bg-primary">
                <h2 class="mb-0 text-white"><i class="fas fa-file-signature text-white"></i> All Leases</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-center">Tenant ID</th>
                                <th class="text-center">Room Number</th>
                                <th class="text-center">Start Date</th>
                                <th class="text-center">End Date</th>
                                <th class="text-center">Lease Agreement</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($leases as $lease)
                                <tr>
                                    <td class="text-center">{{ $lease->tenant_id }}</td>
                                    <td class="text-center">{{ $lease->room_number }}</td>
                                    <td class="text-center">{{ $lease->start_date }}</td>
                                    <td class="text-center">{{ $lease->end_date }}</td>
                                    <td class="text-center">
                                        <a href="{{ asset('storage/' . $lease->lease_agreement) }}" target="_blank"><i
                                                class="fas fa-file-alt"></i> View Lease Agreement</a>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('leases.edit', $lease) }}" class="btn btn-info btn-sm"><i
                                                class="fas fa-edit"></i> Edit</a>
                                        <form action="{{ route('leases.destroy', $lease) }}" method="POST"
                                            class="d-inline-block"
                                            onsubmit="return confirm('Are you sure you want to delete this lease?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i>
                                                Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted">No leases found!</td>
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
        setTimeout(function () {
            var flashMessage = document.getElementById('flash-message');
            if (flashMessage) {
                flashMessage.style.display = 'none';
            }
        }, 2000); // 2000 milliseconds = 2 seconds
    </script>
</main>
@endsection