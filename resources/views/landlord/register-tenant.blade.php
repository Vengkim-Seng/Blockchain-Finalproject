@extends('layout.dashboard-parent-landlord')

@section('content')

    @parent <!-- Retain master layout content -->

    <!-- Main Content Area -->
    <main class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200 p-4 sm:p-6 lg:p-8">
        
        <!-- Form Container -->
        <div class="container mt-5">
            <div class="card shadow-sm">
                <div class="card-header bg-primary">
                    <h2 class="mb-0 text-white"><i class="fas fa-user-plus text-white"></i> Register New Tenant</h2>
                </div>
                <div class="card-body">
                    @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <form id="registrationForm" method="POST" action="{{ route('tenant.register.submit') }}">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="tenant_name" class="fw-bold text-dark">Name</label>
                                <input type="text" class="form-control @error('tenant_name') is-invalid @enderror" id="tenant_name" name="tenant_name" placeholder="Enter tenant name" required>
                                @error('tenant_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="email" class="fw-bold text-dark">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Enter tenant email" required>
                                @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="contact_info" class="fw-bold text-dark">Contact Information</label>
                                <input type="text" class="form-control @error('contact_info') is-invalid @enderror" id="contact_info" name="contact_info" placeholder="Enter contact information" required>
                                @error('contact_info')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="password" class="fw-bold text-dark">Login Password</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter login password" required>
                                @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-12">
                                <button type="submit" class="btn btn-primary"><i class="fas fa-check"></i> Submit</button>
                                <button type="button" class="btn btn-secondary ml-2" onclick="clearForm('registrationForm')"><i class="fas fa-times"></i> Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>


    </main>
    <!-- End Main Content Area -->

@endsection
