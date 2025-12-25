<!-- Profil du praticien -->
<div class="px-4 py-6 border-b border-blue-800" x-show="sidebarOpen">
    <div class="flex items-center space-x-3">
        <div class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-400 to-purple-500 flex items-center justify-center text-white text-2xl font-bold">
            {{ substr(auth()->user()->prenom, 0, 1) }}{{ substr(auth()->user()->name, 0, 1) }}
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-bold text-white truncate">Dr. {{ auth()->user()->nom_complet }}</p>
            <p class="text-xs text-blue-200 truncate">{{ auth()->user()->praticien->specialites->first()->nom ?? 'Praticien' }}</p>
        </div>
    </div>
</div>

<a href="{{ route('praticien.dashboard') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('praticien.dashboard') ? 'bg-blue-800 text-white' : 'hover:bg-blue-800' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
    </svg>
    <span x-show="sidebarOpen">Tableau de bord</span>
</a>
<a href="{{ route('praticien.profile') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('praticien.profile') ? 'bg-blue-800 text-white' : 'hover:bg-blue-800' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
    </svg>
    <span x-show="sidebarOpen">Mon Profil</span>
</a>
<a href="{{ route('praticien.agenda') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('praticien.agenda') ? 'bg-blue-800 text-white' : 'hover:bg-blue-800' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
    </svg>
    <span x-show="sidebarOpen">Mon Agenda</span>
</a>
<a href="{{ route('praticien.disponibilites') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('praticien.disponibilites') ? 'bg-blue-800 text-white' : 'hover:bg-blue-800' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>
    <span x-show="sidebarOpen">Disponibilités</span>
</a>
<a href="{{ route('praticien.consultations') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('praticien.consultations') ? 'bg-blue-800 text-white' : 'hover:bg-blue-800' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
    </svg>
    <span x-show="sidebarOpen">Consultations</span>
</a>


