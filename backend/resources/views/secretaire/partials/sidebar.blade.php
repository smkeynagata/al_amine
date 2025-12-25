<a href="{{ route('secretaire.dashboard') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('secretaire.dashboard') ? 'bg-blue-800 text-white' : 'hover:bg-blue-800' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
    </svg>
    <span x-show="sidebarOpen">Tableau de bord</span>
</a>
<a href="{{ route('secretaire.file-attente') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('secretaire.file-attente') ? 'bg-blue-800 text-white' : 'hover:bg-blue-800' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
    </svg>
    <span x-show="sidebarOpen">File d'attente</span>
</a>
<a href="{{ route('secretaire.agendas') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('secretaire.agendas') ? 'bg-blue-800 text-white' : 'hover:bg-blue-800' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
    </svg>
    <span x-show="sidebarOpen">Agendas</span>
</a>
<a href="{{ route('secretaire.facturation') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('secretaire.facturation') ? 'bg-blue-800 text-white' : 'hover:bg-blue-800' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
    </svg>
    <span x-show="sidebarOpen">Facturation</span>
</a>
<a href="{{ route('secretaire.encaissements') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('secretaire.encaissements') ? 'bg-blue-800 text-white' : 'hover:bg-blue-800' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
    </svg>
    <span x-show="sidebarOpen">Encaissements</span>
</a>

