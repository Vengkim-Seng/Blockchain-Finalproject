@extends('layout.dashboard-parent-landlord')

@section('content')

    @parent <!-- Retain master layout content -->

    <!-- Main Content Area -->
    <main class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200 p-4 sm:p-6 lg:p-8">
        
       <!-- Form Container -->
        <div class="container mt-5">
            <div class="card shadow-sm">
                <div class="card-header bg-primary">
                    <h2 class="mb-0 text-white"><i class="fas fa-money-bill-wave text-white"></i> Add Rent Payment</h2>
                </div>
                <div class="card-body">
                    <!-- Success Message -->
                    @if (session('success'))
                    <div id="flash-message" class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                    @endif

                    <!-- Error Messages -->
                    @if ($errors->any())
                    <div id="flash-message" class="alert alert-danger" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form id="rentPaymentForm" method="POST" action="{{ route('rent.store') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="tenant_name" class="fw-bold text-dark">Tenant</label>
                                <select name="tenant_name" id="tenant_name" class="form-control @error('tenant_name') is-invalid @enderror" required>
                                    <option value="">Select a tenant</option>
                                    @foreach ($tenantsWithActiveLeases as $tenant)
                                    <option value="{{ $tenant->tenant_name }}">{{ $tenant->tenant_name }}</option>
                                    @endforeach
                                </select>
                                @error('tenant_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="payment_date" class="fw-bold text-dark">Rent Due Date</label>
                                <input type="date" class="form-control @error('payment_date') is-invalid @enderror" id="payment_date" name="payment_date" required>
                                @error('payment_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="amount" class="fw-bold text-dark">Amount ($)</label>
                                <input type="number" step="0.01" class="form-control @error('amount') is-invalid @enderror" id="amount" name="amount" placeholder="Enter amount" required>
                                @error('amount')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Add Rent Payment</button>
                                <button type="button" class="btn btn-secondary ml-2" onclick="clearForm('rentPaymentForm')"><i class="fas fa-times"></i> Cancel</button>
                            </div>
                        </div>
                    </form>
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
    <!-- End Main Content Area -->

@endsection
