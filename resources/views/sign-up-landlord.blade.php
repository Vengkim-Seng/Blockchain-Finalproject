@extends('layout.home-master')

@section('content')

<section class="flex flex-col items-center justify-start px-6 py-8 mx-auto">
    <div class="w-full rounded-lg shadow border border-gray-300 md:mt-0 sm:max-w-md xl:p-0 bg-[#3661e3]">
        <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
            <h1 class="text-xl font-bold leading-tight tracking-tight text-white md:text-2xl">
                Create A Landlord Account
            </h1>
            <form class="space-y-4 md:space-y-6" action="{{ route('register.landlord') }}" method="POST">
                @csrf
                <div>
                    <label for="name" class="block mb-2 text-sm font-medium text-white">Name</label>
                    <input type="text" name="landlord_name" id="landlord_name" class="bg-white border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-300 focus:border-blue-300 block w-full p-2.5" required>
                </div>                    
                <div>
                    <label for="email" class="block mb-2 text-sm font-medium text-white">Email Address</label>
                    <input type="email" name="email" id="email" class="bg-white border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-300 focus:border-blue-300 block w-full p-2.5" required>
                </div>
                <div>
                    <label for="contact_info" class="block mb-2 text-sm font-medium text-white">Contact Information</label>
                    <input type="text" name="contact_info" id="contact_info" class="bg-white border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-300 focus:border-blue-300 block w-full p-2.5" required>
                </div>
                <div>
                    <label for="password" class="block mb-2 text-sm font-medium text-white">Password</label>
                    <input type="password" name="password" id="password" class="bg-white border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-300 focus:border-blue-300 block w-full p-2.5" required>
                </div>
                <div>
                    <label for="password_confirmation" class="block mb-2 text-sm font-medium text-white">Confirm password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="bg-white border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-blue-300 focus:border-blue-300 block w-full p-2.5" required>
                </div>
                @if ($errors->any())
                <div style="background-color: #fed7d7; border-color: #f5a094; color: #c53030; padding: 0.75rem; border-width: 1px; border-style: solid; border-radius: 0.375rem;" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
                <button type="submit" class="w-full bg-white text-[#1489ec] hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 font-bold rounded-lg text-sm px-4 sm:px-5 py-2 sm:py-2.5 text-center transition duration-300 ease-in-out">Create an account</button>
                <p class="text-sm font-light text-gray-300 dark:text-gray-200">
                    Already have a landlord account? <a href="/login-landlord" class="font-medium text-white hover:underline">Login here</a>
                </p>
            </form>
        </div>
    </div>
</section>


@endsection
