<header class="sm:flex sm:justify-between sm:items-center py-5 mt-5">
    <div class="container mx-auto">
        <div class="flex flex-col sm:flex-row justify-between items-center p-5">
            <div class="flex items-center space-x-2 mb-3 sm:mb-0">
                <a href="/" class="flex items-center space-x-2">
                    <i class="fas fa-home text-blue-800 text-2xl"></i>
                    <span class="font-bold text-2xl text-gray-800">RENTHUB</span>
                </a>
            </div>
            <nav class="sm:flex sm:items-center">
                <ul class="flex space-x-4">
                    <li>
                        <div class="relative">
                            <a href="#" class="text-gray-700 hover:text-gray-900">Login</a>
                            <div class="absolute right-0 mt-2 w-48 bg-white border rounded-lg shadow-lg hidden">
                                <ul class="py-1">
                                    <li><a href="/login-landlord"
                                            class="block px-4 py-2 text-gray-800 hover:bg-gray-200">As Landlord</a></li>
                                    <li><a href="/login-tenant"
                                            class="block px-4 py-2 text-gray-800 hover:bg-gray-200">As Tenant</a></li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <li><a href="/signup" class="px-4 py-2 rounded bg-blue-500 text-white hover:bg-blue-600">Sign up</a>
                    </li>
                    <li><a href="/All-landlords-Table" class="text-gray-700 hover:text-gray-900">Blockchain Table</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</header>

{{-- Include the JavaScript file --}}
<script src="{{ asset('assets/js/login-dropdown.js') }}"></script>