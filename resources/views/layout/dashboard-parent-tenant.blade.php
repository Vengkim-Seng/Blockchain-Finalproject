@extends('layout.dashboard-master')

@section('content')

<!-- sidenav  -->
<aside class="max-w-62.5 ease-nav-brand z-990 fixed inset-y-0 my-4 ml-4 block w-full -translate-x-full flex-wrap items-center justify-between overflow-y-auto rounded-2xl border-0 p-0 antialiased shadow-none transition-transform duration-200 xl:left-0 xl:translate-x-0" style="background-color: #3f87e5;">
    <div class="h-19.5">
        <i class="absolute top-0 right-0 hidden p-4 opacity-50 cursor-pointer fas fa-times text-white xl:hidden" sidenav-close></i>
        <a class="block px-8 py-6 m-0 text-sm whitespace-nowrap text-white">
            <i class="fas fa-home text-2xl"></i>
            <span class="ml-1 font-semibold">Tenant Dashboard</span>
        </a>
    </div>

    <hr class="h-px mt-0 bg-transparent bg-gradient-to-r from-transparent via-black/40 to-transparent" />

    <div class="items-center block w-auto max-h-screen overflow-auto h-sidenav grow basis-full">
        <ul class="flex flex-col pl-0 mb-0">
        
                <li class="mt-0.5 w-full">
                    <a href="/tenant/dashboard" class="py-2.7 text-sm my-0 mx-4 flex items-center whitespace-nowrap px-4 text-white">
                        <i class="fas fa-chart-pie mr-2 text-xl"></i>
                        <span>Home</span>
                    </a>
                </li>

                <li class="mt-0.5 w-full">
                    <a class="py-2.7 text-sm my-0 mx-4 flex items-center whitespace-nowrap px-4 text-white">
                        <i class="fas fa-table mr-2 text-xl"></i>
                        <span>Lease</span>
                        <i class="fas fa-caret-down ml-auto"></i>
                    </a>
                    <ul class="hidden">
                        <li><a href="/tenant/leases" class="py-2.7 text-sm my-0 mx-4 flex items-center whitespace-nowrap px-4 text-white">My Lease</a></li>
                    </ul>
                </li>
                <li class="mt-0.5 w-full">
                    <a class="py-2.7 text-sm my-0 mx-4 flex items-center whitespace-nowrap px-4 text-white">
                        <i class="fas fa-credit-card mr-2 text-xl"></i>
                        <span>Rent</span>
                        <i class="fas fa-caret-down ml-auto"></i>
                    </a>
                    <ul class="hidden">
                        <li><a href="/tenant/rent" class="py-2.7 text-sm my-0 mx-4 flex items-center whitespace-nowrap px-4 text-white">My Rent</a></li>
                    </ul>
                </li>
                <li class="mt-0.5 w-full">
                    <a class="py-2.7 text-sm my-0 mx-4 flex items-center whitespace-nowrap px-4 text-white">
                        <i class="fas fa-vr-cardboard mr-2 text-xl"></i>
                        <span>Utility</span>
                        <i class="fas fa-caret-down ml-auto"></i>
                    </a>
                    <ul class="hidden">
                        <li><a href="/tenant/utility" class="py-2.7 text-sm my-0 mx-4 flex items-center whitespace-nowrap px-4 text-white">My Utility</a></li>
                    </ul>
                </li>
                <li class="w-full mt-4">
                    <h6 class="pl-6 ml-2 text-xs font-bold leading-tight uppercase text-white">Account Page</h6>
                </li>
                <li class="mt-0.5 w-full">
                    <a class="py-2.7 text-sm my-0 mx-4 flex items-center whitespace-nowrap px-4 text-white">
                        <i class="fas fa-user-circle mr-2 text-xl"></i>
                        <span>Profile</span>
                        <i class="fas fa-caret-down ml-auto"></i>
                    </a>
                    <ul class="hidden">
                    <li><a href="{{ route('tenant.profile') }}" class="py-2.7 text-sm my-0 mx-4 flex items-center whitespace-nowrap px-4 text-white">Show Profile</a></li>
                        <li><a href="/tenant/change-password" class="py-2.7 text-sm my-0 mx-4 flex items-center whitespace-nowrap px-4 text-white">Change Password</a></li>
                    </ul>
                </li>

        </ul>
    </div>

</aside>      
<!-- end sidenav -->

<!-- Navbar -->
{{-- <main class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200">
    <nav class="relative flex flex-wrap items-center justify-between px-0 py-2 mx-6 transition-all shadow-none duration-250 ease-soft-in rounded-2xl lg:flex-nowrap lg:justify-start" navbar-main navbar-scroll="true">
        <div class="flex items-center justify-between w-full px-4 py-1 mx-auto flex-wrap-inherit">
            <nav>
                <ol class="flex flex-wrap pt-1 mr-12 bg-transparent rounded-lg sm:mr-16">
                    <li class="text-sm leading-normal">
                        <a class="opacity-50 text-slate-700" href="javascript:;">Pages</a>
                    </li>
                    <li class="text-sm pl-2 capitalize leading-normal text-slate-700 before:float-left before:pr-2 before:text-gray-600 before:content-['/']" aria-current="page">Dashboard</li>
                </ol>
                <h6 class="mb-0 font-bold capitalize">Home</h6>
            </nav>

            <div class="flex items-center mt-2 grow sm:mt-0 sm:mr-6 md:mr-0 lg:flex lg:basis-auto">
                <div class="flex items-center md:ml-auto md:pr-4">
                    <div class="relative flex flex-wrap items-stretch w-full transition-all rounded-lg ease-soft">
                    </div>
                </div>

                <ul class="flex flex-row justify-end pl-0 mb-0 list-none md-max:w-full">
                    <li class="relative">
                        <div class="dropdown-toggle">
                        <a href="#" class="block px-0 py-2 text-sm font-semibold transition-all ease-nav-brand text-slate-500 flex items-center" id="dropdownToggle" onclick="toggleDropdown()">
    <img src="{{ $tenant->profile_picture ? asset('storage/' . $tenant->profile_picture) : asset('assets/img/default-icon.png') }}" alt="Profile Picture" class="w-8 h-8 rounded-full mr-2" style="width: 32px; height: 32px;" />
                                <span class="hidden sm:inline">{{ $tenant->tenant_name }}</span>
                                <i class="fa fa-angle-down ml-2"></i>
                            </a>
                        </div>
                        <div id="dropdownMenu" class="absolute right-0 z-10 hidden dropdown-menu" style="top: 100%; min-width: 120px;"> <!-- Set a min-width here -->
                            <ul class="py-1 bg-white rounded-md shadow-lg">
                                <li>
                                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-4 py-2 text-sm text-gray-700 hover:bg-blue-500 hover:text-blue hover:font-bold">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                                    </a>
                                    
                                    <form id="logout-form" action="{{ route('logout-tenant') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </li>
                            </ul>                            

                        </div>
                    </li>
                </ul>
                 
            </div>
        </div>
    </nav>
</main>   --}}
<!-- Navbar -->

<main class="ease-soft-in-out xl:ml-68.5 relative h-full max-h-screen rounded-xl transition-all duration-200">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid">
            <div class="d-flex justify-content-between w-100 align-items-center">
                <nav aria-label="breadcrumb" class="mb-2">
                    <!-- breadcrumb -->
                    <ol class="breadcrumb pt-3 bg-transparent rounded-lg mb-0" style="margin-bottom: 0 !important;">
                        <li class="breadcrumb-item">
                            <a class="opacity-50 text-slate-700" href="javascript:;">Pages</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
                    </ol>
                </nav>

                <ul class="navbar-nav ml-auto d-flex align-items-center">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ $tenant->profile_picture ? asset('storage/' . $tenant->profile_picture) : asset('assets/img/default-icon.png') }}" alt="Profile Picture" class="w-8 h-8 rounded-full mr-2" />
                            <span class="d-none d-sm-inline">{{ $tenant->tenant_name }}</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('tenant.profile') }}">Profile</a>
                            <a class="dropdown-item" href="/tenant/change-password">Change Password</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
                            <form id="logout-form" action="{{ route('logout-tenant') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</main>
<!-- end Navbar -->


@endsection