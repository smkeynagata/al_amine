<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Praticien') - Al-Amine RDV</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    @stack('styles')
</head>
<body class="bg-gradient-to-br from-blue-900 via-blue-800 to-blue-900 font-sans min-h-screen">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <aside
            x-data="{ unreadMessagesCount: window.__chatUnreadCount ?? 0, updateUnreadCount(count) { this.unreadMessagesCount = count; } }"
            x-init="window.addEventListener('chat:unread-count-changed', (event) => updateUnreadCount(event.detail));"
            class="w-64 bg-gradient-to-b from-blue-950 via-blue-900 to-blue-950 text-white flex-shrink-0 hidden lg:flex flex-col shadow-2xl"
        >
            <!-- Logo/Header -->
            <div class="p-6 border-b border-blue-800/60">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('photos/logoalmine.png') }}" alt="Al-Amine RDV" class="h-16 w-auto object-contain">
                </div>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <a href="{{ route('praticien.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('praticien.dashboard') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                <a href="{{ route('praticien.patients') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('praticien.patients') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <span class="font-medium">Mes patients</span>
                </a>

                <a href="{{ route('praticien.documents') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('praticien.documents') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <span class="font-medium">Documents</span>
                </a>

                <a href="{{ route('praticien.agenda') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('praticien.agenda') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <span class="font-medium">Agenda</span>
                </a>

                <a href="{{ route('praticien.consultations') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('praticien.consultations') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <span class="font-medium">Consultations</span>
                </a>

                <a href="{{ route('praticien.messages.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('praticien.messages.*') ? 'bg-white/20 text-white' : 'text-blue-100 hover:bg-white/10 hover:text-white' }}"
                   :class="!('{{ request()->routeIs('praticien.messages.*') }}') && unreadMessagesCount > 0 ? 'bg-indigo-700/30 text-white' : ''"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V7a2 2 0 012-2h14a2 2 0 012 2v7a2 2 0 01-2 2h-4l-4 4v-4z"></path>
                    </svg>
                    <span class="font-medium relative flex items-center gap-2">
                        Messages
                        <span x-show="unreadMessagesCount > 0" x-text="unreadMessagesCount" class="inline-flex min-w-[1.5rem] items-center justify-center rounded-full bg-red-500 px-2 py-0.5 text-xs font-semibold text-white"></span>
                    </span>
                </a>

                {{-- Future messaging module --}}
                {{--
                <a href="{{ route('praticien.chats') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition {{ request()->routeIs('praticien.chats') ? 'bg-white/20 text-white' : 'text-slate-200 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                    </svg>
                    <span class="font-medium">Messages</span>
                </a>
                --}}
            </nav>

            <!-- User Section -->
            <div class="p-4 border-t border-blue-800/60">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center bg-white/20 text-white font-bold text-sm">
                        {{ substr(auth()->user()->prenom ?? auth()->user()->name, 0, 1) }}{{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm truncate">Dr. {{ auth()->user()->name }}</p>
                        <p class="text-xs text-indigo-300 truncate">{{ auth()->user()->email }}</p>
                    </div>
                </div>
                <a href="{{ route('praticien.profile') }}" class="w-full flex items-center justify-center gap-2 px-4 py-2 mb-2 bg-white/15 hover:bg-white/25 rounded-lg text-sm font-medium text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm0 0c-3.866 0-7 2.239-7 5v1a1 1 0 001 1h12a1 1 0 001-1v-1c0-2.761-3.134-5-7-5z" />
                    </svg>
                    Mon profil
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 rounded-lg text-sm font-medium transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Déconnexion
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Top Bar -->
            <header class="bg-gradient-to-r from-blue-800/90 to-blue-700/80 border-b border-blue-600/60 px-6 py-4 flex items-center justify-between text-white">
                <div>
                    <h2 class="text-2xl font-bold">@yield('title', 'Tableau de bord')</h2>
                    <p class="text-sm text-blue-100 mt-1">{{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</p>
                </div>

                <div class="flex items-center gap-4">
                    <button class="relative p-2 text-blue-100 hover:bg-blue-700/40 rounded-lg transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>

                    <div class="lg:hidden flex items-center gap-2">
                        <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center text-white font-bold text-xs">
                            {{ substr(auth()->user()->prenom ?? auth()->user()->name, 0, 1) }}{{ substr(auth()->user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                @if(session('success'))
                <div class="bg-green-100 border border-green-300 text-green-900 px-6 py-4 rounded-lg mb-6 flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                @endif

                @if(session('error'))
                <div class="bg-red-100 border border-red-300 text-red-900 px-6 py-4 rounded-lg mb-6 flex items-center gap-3">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                @endif

                <div class="bg-gradient-to-br from-white/95 to-white/75 backdrop-blur-sm rounded-2xl p-6 shadow-xl">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @stack('scripts')
</body>
</html>

