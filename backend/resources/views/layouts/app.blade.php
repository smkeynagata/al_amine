<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Al-Amine RDV')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('styles')
</head>
<body class="bg-gradient-to-br from-gray-100 via-gray-50 to-gray-900 min-h-screen">
    <div class="min-h-screen flex" x-data="{ sidebarOpen: true }">
        <!-- Sidebar -->
        <aside :class="sidebarOpen ? 'w-64' : 'w-20'" class="bg-gradient-to-b from-blue-950 via-blue-900 to-blue-950 text-white transition-all duration-300 fixed h-full z-30 shadow-2xl">
            <div class="p-4 flex items-center justify-between border-b border-blue-800/50">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('photos/logoalmine.png') }}" alt="Al-Amine RDV" class="h-16 w-auto object-contain">
                </div>
                <button @click="sidebarOpen = !sidebarOpen" class="p-2 hover:bg-blue-800/50 rounded transition-colors duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <nav class="mt-4">
                @yield('sidebar')
            </nav>
        </aside>

        <!-- Main Content -->
        <div :class="sidebarOpen ? 'ml-64' : 'ml-20'" class="flex-1 transition-all duration-300 bg-gradient-to-br from-blue-900 via-blue-800 to-blue-900 min-h-screen">
            <!-- Header -->
            <header class="bg-gradient-to-r from-blue-800/80 to-blue-700/60 backdrop-blur-sm shadow-lg border-b border-blue-600 sticky top-0 z-20">
                <div class="px-6 py-4 flex justify-between items-center">
                    <div>
                        <h2 class="text-2xl font-bold text-white">@yield('page-title')</h2>
                        <p class="text-sm text-blue-100">@yield('breadcrumb')</p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <div class="text-right">
                            <p class="text-sm font-semibold text-white">{{ auth()->user()->nom_complet }}</p>
                            <p class="text-xs text-blue-100">{{ ucfirst(strtolower(auth()->user()->role)) }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm">
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            @if(session('success'))
            <div class="mx-6 mt-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
            @endif

            @if(session('error'))
            <div class="mx-6 mt-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
            @endif

            <!-- Page Content -->
            <main class="p-6">
                <div class="bg-gradient-to-br from-white/90 to-white/70 backdrop-blur-sm rounded-2xl p-6 shadow-lg">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>

