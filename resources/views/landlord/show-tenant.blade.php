@extends('layout.dashboard-parent-landlord')

@section('content')
    @parent <!-- Retain master layout content -->
    <main class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200 p-4 sm:p-6 lg:p-8">

        <!-- Success Message -->
        @if (session('success'))
        <div id="flash-message" class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Success!</strong> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <!-- Tenants Table -->
        <div class="container">
        <div class="card shadow-sm">
            <div class="card-header bg-primary">
                <h2 class="mb-0 text-white"><i class="fas fa-users text-white"></i> Tenants</h2>
            </div>
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead class="thead-dark">
                        <tr>
                            <th class="text-center">Tenant Name</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Contact Info</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tenants as $tenant)
                            <tr>
                                <td class="text-center">{{ $tenant->tenant_name }}</td>
                                <td class="text-center">{{ $tenant->email }}</td>
                                <td class="text-center">{{ $tenant->contact_info }}</td>
                                <td class="text-center">
                                    <form action="{{ route('tenant.destroy', ['tenant_id' => $tenant->tenant_id]) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Are you sure you want to delete this tenant?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">There are no tenants!</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        </div>


        <!-- Pagination Links -->
        <div class="mt-4">
            {{ $tenants->links() }}
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