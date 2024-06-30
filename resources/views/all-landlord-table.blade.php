@extends('layout.home-master')

@section('content')

@parent

<!-- Main Content Area -->
<main class="px-5 md:px-16 py-10">
    <nav class="bg-white-800 text-black text-2xl font-bold p-4 flex justify-center">
        <ul class="flex space-x-12 items-center">
            <li><a href="/All-landlords-Table" class="hover:text-gray-300">Landlord</a></li>
            <li><a href="/all-tenants-table" class="hover:text-gray-300">Tenant</a></li>
            <li><a href="/all-lease-table" class="hover:text-gray-300">Lease</a></li>
        </ul>
    </nav>
    <!-- Landlords Table Container -->
    <div class="container mx-auto">
        <div class="card shadow-sm">
            <div class="card-header bg-primary">
                <h3>Data is valid: <b>{{ $valid ? 'True' : 'False' }}</b></h3>
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

                <!-- Landlords Table -->
                <div class="overflow-x-auto w-full flex justify-center">
                    <table class="min-w-full bg-white border border-gray-200 table-auto w-full max-w-6xl">
                        <thead class="bg-blue-500 text-white text-center">
                            <tr>
                                <th class="py-2 px-4 border-b">ID</th>
                                <th class="py-2 px-4 border-b">Landlord Name</th>
                                <th class="py-2 px-4 border-b">Status</th>
                                <th class="py-2 px-4 border-b">Version</th>
                                <th class="py-2 px-4 border-b">Previous Record ID</th>
                                <th class="py-2 px-4 border-b">Previous Hash</th>
                                <th class="py-2 px-4 border-b">Current Hash</th>
                            </tr>
                        </thead>
                        <tbody class="text-center">
                            @foreach ($landlords as $landlord)
                                                        @php
                                                            $hashColor = '#' . substr(md5($landlord->previous_hash), 0, 6);
                                                            $currentHashColor = '#' . substr(md5($landlord->current_hash), 0, 6);
                                                        @endphp
                                                        <tr class="hover:bg-gray-100">
                                                            <td class="py-2 px-4 border-b text-sm">{{ $landlord->id }}</td>
                                                            <td class="py-2 px-4 border-b text-sm">{{ $landlord->landlord_name }}</td>
                                                            <td class="py-2 px-4 border-b text-sm">
                                                                <span class="px-2 py-1 rounded-full text-white 
                                                                                    @if($landlord->status == 'INSERT') bg-green-500 
                                                                                    @elseif($landlord->status == 'UPDATE') bg-blue-500 
                                                                                    @elseif($landlord->status == 'DELETE') bg-red-500 
                                                                                    @endif">
                                                                    {{ $landlord->status }}
                                                                </span>
                                                            </td>
                                                            <td class="py-2 px-4 border-b text-sm">{{ $landlord->version }}</td>
                                                            <td class="py-2 px-4 border-b text-sm">{{ $landlord->previous_record_id }}</td>
                                                            <td class="py-2 px-4 border-b text-sm" style="background-color: {{ $hashColor }}">
                                                                {{ $landlord->previous_hash }}
                                                            </td>
                                                            <td class="py-2 px-4 border-b text-sm"
                                                                style="background-color: {{ $currentHashColor }}">
                                                                {{ $landlord->current_hash }}
                                                            </td>
                                                        </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        setTimeout(function () {
            var flashMessage = document.getElementById('flash-message');
            if (flashMessage) {
                flashMessage.style.display = 'none';
            }
        }, 2000);
    </script>
</main>

@endsection