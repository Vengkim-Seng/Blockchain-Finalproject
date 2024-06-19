@extends('layout.home-master')

@section('content')
    <main class="px-5 md:px-16 py-10">
        <div class="container mx-auto mb-10">
            {{-- Hero --}}
            <section class="flex flex-col md:flex-row mb-10 md:mb-32">
                <div class="w-full md:w-5/12 flex flex-col justify-center">
                    <div class="px-8">
                        <h1 class="text-3xl font-bold text-gray-800">With the help of <span class="text-blue-700">RENTHUB</span> it's now easier to centralize & streamline your <span class="text-blue-700">room rental management tasks</span></h1>
                        <p class="text-gray-600 my-4">Your complete solution for easy and transparent room rental management, including lease agreements, rent collection, and utility expenses.</p>
                        <a href="/signup" class="inline-block px-6 py-3 bg-blue-600 text-white rounded hover:bg-blue-700 transition-colors font-bold">REGISTER NOW</a>
                    </div>
                </div>
                <div class="w-full md:w-7/12 flex justify-center items-center mt-6 md:mt-0">
                    <img src="assets/img/hero.png" alt="Hero Image" class="w-full max-w-lg md:max-w-2xl">
                </div>
            </section>
            {{-- End Hero --}}

            {{-- Key feature --}}
            <section class="flex flex-col items-center">
                <div class="w-full mb-5 text-center mt-10">
                    <h2 class="text-3xl font-semibold text-gray-800 mb-3">Key Features</h2>
                </div>
                <div class="w-full md:w-10/12 lg:w-10/12 px-4">
                    <div class="flex flex-wrap -mx-4 justify-center">
                        <div class="w-full md:w-4/12 lg:w-4/12 px-4 mb-6">
                            <div class="text-center">
                                <div class="mb-3">
                                    <i class="fas fa-file-contract text-blue-500 text-3xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-3">Lease Management</h3>
                                <p class="text-gray-600">
                                    Digitally store and track lease agreements, including start and end dates, and rent amounts
                                </p>
                            </div>
                        </div>
                        <div class="w-full md:w-4/12 lg:w-4/12 px-4 mb-6">
                            <div class="text-center">
                                <div class="mb-3">
                                    <i class="fas fa-hand-holding-usd text-blue-500 text-3xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-3">Rent Collection</h3>
                                <p class="text-gray-600">
                                    Tenants can view rent amounts, due dates, and upload payment transactions for easy tracking
                                </p>
                            </div>
                        </div>
                        <div class="w-full md:w-4/12 lg:w-4/12 px-4 mb-6">
                            <div class="text-center">
                                <div class="mb-3">
                                    <i class="fas fa-bolt text-blue-500 text-3xl"></i>
                                </div>
                                <h3 class="text-lg font-semibold text-gray-800 mb-3">Utility Management</h3>
                                <p class="text-gray-600">
                                    Automatically calculate utility expenses and generate bills or invoices for tenants.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            {{-- End key feature --}}

            {{-- Why choose renthub --}}   
            <section class="py-10">
                <div class="container mx-auto px-8 text-center mt-10 mb-10">
                    <h2 class="text-3xl font-semibold text-gray-800 mb-8">Why Choose RentHub?</h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Centralized Platform Feature -->
                        <div class="p-4 flex flex-col items-center text-center">
                            <div class="mb-2">
                                <i class="fas fa-cogs text-blue-500 text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Centralized Platform</h3>
                            <p class="text-gray-600">Manage all rental tasks in one place.</p>
                        </div>
                        <!-- Enhanced Efficiency Feature -->
                        <div class="p-4 flex flex-col items-center text-center">
                            <div class="mb-2">
                                <i class="fas fa-rocket text-blue-500 text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Enhanced Efficiency</h3>
                            <p class="text-gray-600">Save time with streamlined processes.</p>
                        </div>
                        <!-- Improved Transparency Feature -->
                        <div class="p-4 flex flex-col items-center text-center">
                            <div class="mb-2">
                                <i class="fas fa-search text-blue-500 text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Improved Transparency</h3>
                            <p class="text-gray-600">Clear communication between landlords and tenants.</p>
                        </div>
                        <!-- Convenience Feature -->
                        <div class="p-4 flex flex-col items-center text-center">
                            <div class="mb-2">
                                <i class="fas fa-thumbs-up text-blue-500 text-3xl"></i>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-2">Convenience</h3>
                            <p class="text-gray-600">Accessible anytime, anywhere.</p>
                        </div>
                    </div>
                </div>
            </section>
            {{-- End why choose renthub --}}

            {{-- Call to action --}}
            <section class="bg-blue-600 text-white text-center py-20 rounded-lg">
                <div class="container mx-auto px-8">
                    <h2 class="text-xl uppercase mb-6">Are you a landlord?</h2>
                    <h3 class="text-5xl font-semibold mb-10">Join RentHub For <br> Improved Rental <br> Management</h3>
                    <a href="/signup" class="inline-block px-8 py-4 bg-white text-blue-600 rounded hover:bg-gray-100 transition-colors font-bold">GET STARTED NOW</a>
                </div>
            </section>            
            {{-- End call to action --}}
        </div>
    </main>
@endsection

