@extends('layout.dashboard-parent-landlord')

@section('content')
@parent <!-- Retain master layout content -->
<main
    class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200 p-4 sm:p-6 lg:p-8">

    <!-- Form Container -->
    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-header bg-primary">
                <h2 class="mb-0 text-white"><i class="fas fa-edit text-white"></i> Edit Lease</h2>
            </div>
            <div class="card-body">
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

                <!-- Edit Lease Form -->
                <form id="editLeaseForm" method="POST" action="{{ route('leases.update', $lease->lease_id) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="tenantName" class="fw-bold text-dark">Tenant</label>
                            <input type="text" id="tenantName" class="form-control"
                                value="{{ $lease->tenant->tenant_name }}" readonly>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="roomNumber" class="fw-bold text-dark">Room Number</label>
                            <input type="text" id="roomNumber"
                                class="form-control @error('room_number') is-invalid @enderror" name="room_number"
                                value="{{ $lease->room_number }}" required>
                            @error('room_number')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="startDate" class="fw-bold text-dark">Start Date</label>
                            <input type="date" id="startDate"
                                class="form-control @error('start_date') is-invalid @enderror" name="start_date"
                                value="{{ $lease->start_date }}" required>
                            @error('start_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="endDate" class="fw-bold text-dark">End Date</label>
                            <input type="date" id="endDate" class="form-control @error('end_date') is-invalid @enderror"
                                name="end_date" value="{{ $lease->end_date }}" required>
                            @error('end_date')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="leaseAgreement" class="fw-bold text-dark">Lease Agreement (PDF)</label>
                        <input type="file" id="leaseAgreement"
                            class="form-control-file @error('lease_agreement') is-invalid @enderror"
                            name="lease_agreement" accept=".pdf">
                        @if ($lease->lease_agreement)
                            <small class="form-text text-muted">Current Lease Agreement: <a
                                    href="{{ asset('storage/' . $lease->lease_agreement) }}"
                                    target="_blank">{{ basename($lease->lease_agreement) }}</a></small>
                        @endif
                        @error('lease_agreement')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Update
                                Lease</button>
                            <a href="/leases" class="btn btn-secondary ml-2"><i class="fas fa-times"></i> Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

</main>