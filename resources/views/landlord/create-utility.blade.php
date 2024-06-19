@extends('layout.dashboard-parent-landlord')

@section('content')

    @parent <!-- Retain master layout content -->

    <!-- Main Content Area -->
    <main class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200 p-4 sm:p-6 lg:p-8">
        
        <!-- Form Container -->
        <div class="container mt-5">
            <div class="card shadow-sm">
                <div class="card-header bg-primary">
                    <h2 class="mb-0 text-white"><i class="fas fa-file-invoice-dollar text-white"></i> Add Utility Bill</h2>
                </div>
                <div class="card-body">
                    <!-- Success Message -->
                    @if (session('success'))
                    <div class="alert alert-success" role="alert">
                        {{ session('success') }}
                    </div>
                    @endif

                    <!-- Error Messages -->
                    @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form id="rentPaymentForm" method="POST" action="{{ route('utility.store') }}" enctype="multipart/form-data">
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
                                <label for="billing_date" class="fw-bold text-dark">Billing Date</label>
                                <input type="date" class="form-control @error('billing_date') is-invalid @enderror" id="billing_date" name="billing_date" required>
                                @error('billing_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="previous_electricity_meter_reading" class="fw-bold text-dark">Previous Electricity Meter Reading</label>
                                <input type="number" step="0.01" class="form-control @error('utilities.electricity.previous_meter_reading') is-invalid @enderror" id="previous_electricity_meter_reading" name="utilities[electricity][previous_meter_reading]" placeholder="Enter Previous Electricity Meter Reading" required>
                                @error('utilities.electricity.previous_meter_reading')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="current_electricity_meter_reading" class="fw-bold text-dark">Current Electricity Meter Reading</label>
                                <input type="number" step="0.01" class="form-control @error('utilities.electricity.current_meter_reading') is-invalid @enderror" id="current_electricity_meter_reading" name="utilities[electricity][current_meter_reading]" placeholder="Enter Current Electricity Meter Reading" required>
                                @error('utilities.electricity.current_meter_reading')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="previous_water_meter_reading" class="fw-bold text-dark">Previous Water Meter Reading</label>
                                <input type="number" step="0.01" class="form-control @error('utilities.water.previous_meter_reading') is-invalid @enderror" id="previous_water_meter_reading" name="utilities[water][previous_meter_reading]" placeholder="Enter Previous Water Meter Reading" required>
                                @error('utilities.water.previous_meter_reading')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-6">
                                <label for="current_water_meter_reading" class="fw-bold text-dark">Current Water Meter Reading</label>
                                <input type="number" step="0.01" class="form-control @error('utilities.water.current_meter_reading') is-invalid @enderror" id="current_water_meter_reading" name="utilities[water][current_meter_reading]" placeholder="Enter Current Water Meter Reading" required>
                                @error('utilities.water.current_meter_reading')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>

                            <div class="form-group col-md-12">
                                <label for="proof_of_meter_readings" class="fw-bold text-dark">Upload Proof of Meter Readings</label>
                                <input type="file" class="form-control-file @error('proof_of_meter_readings.*') is-invalid @enderror" id="proof_of_meter_readings" name="proof_of_meter_readings[]" multiple required>
                                @error('proof_of_meter_readings.*')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <!-- Submit button -->
                                <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Add Utility Bill</button>
                                <!-- Cancel button -->
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
