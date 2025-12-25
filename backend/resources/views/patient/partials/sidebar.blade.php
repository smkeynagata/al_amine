<a href="{{ route('patient.dashboard') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('patient.dashboard') ? 'bg-blue-800 text-white' : 'hover:bg-blue-800' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
    </svg>
    <span x-show="sidebarOpen">Tableau de bord</span>
</a>
<a href="{{ route('patient.profile.edit') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('patient.profile.*') ? 'bg-blue-800 text-white' : 'hover:bg-blue-800' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
    </svg>
    <span x-show="sidebarOpen">Mon Profil</span>
</a>
<a href="{{ route('patient.dossier-medical') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('patient.dossier-medical*') ? 'bg-blue-800 text-white' : 'hover:bg-blue-800' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
    </svg>
    <span x-show="sidebarOpen">Dossier Médical</span>
</a>
<a href="{{ route('patient.notifications') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('patient.notifications*') ? 'bg-blue-800 text-white' : 'hover:bg-blue-800' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
    </svg>
    <span x-show="sidebarOpen">Notifications</span>
    @if(auth()->user()->unreadNotifications->count() > 0)
        <span class="ml-auto bg-red-500 text-white text-xs font-bold rounded-full px-2 py-1">
            {{ auth()->user()->unreadNotifications->count() }}
        </span>
    @endif
</a>
<a href="{{ route('patient.messagerie.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('patient.messagerie.*') ? 'bg-blue-800 text-white' : 'hover:bg-blue-800' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
    </svg>
    <span x-show="sidebarOpen">Messagerie</span>
</a>
<a href="{{ route('patient.calendrier') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('patient.calendrier*') ? 'bg-blue-800 text-white' : 'hover:bg-blue-800' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
    </svg>
    <span x-show="sidebarOpen">Calendrier</span>
</a>
<a href="{{ route('patient.demander-rdv') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('patient.demander-rdv') ? 'bg-blue-800 text-white' : 'hover:bg-blue-800' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
    </svg>
    <span x-show="sidebarOpen">Demander RDV</span>
</a>
<a href="{{ route('patient.mes-demandes') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('patient.mes-demandes') ? 'bg-blue-800 text-white' : 'hover:bg-blue-800' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
    </svg>
    <span x-show="sidebarOpen">Mes demandes</span>
</a>
<a href="{{ route('patient.mes-rdv') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('patient.mes-rdv') || request()->routeIs('patient.rendezvous.show') ? 'bg-blue-800 text-white' : 'hover:bg-blue-800' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
    </svg>
    <span x-show="sidebarOpen">Mes RDV</span>
</a>
<a href="{{ route('patient.factures') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('patient.factures') || request()->routeIs('patient.facture.show') || request()->routeIs('patient.paiement*') ? 'bg-blue-800 text-white' : 'hover:bg-blue-800' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
    </svg>
    <span x-show="sidebarOpen">Factures</span>
</a>
<a href="{{ route('patient.paiements.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('patient.paiements.*') ? 'bg-blue-800 text-white' : 'hover:bg-blue-800' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
    </svg>
    <span x-show="sidebarOpen">Paiements</span>
</a>
<a href="{{ route('patient.documents.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('patient.documents.*') ? 'bg-blue-800 text-white' : 'hover:bg-blue-800' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
    </svg>
    <span x-show="sidebarOpen">Documents</span>
</a>
<a href="{{ route('patient.suivi-sante.index') }}" class="flex items-center px-4 py-3 {{ request()->routeIs('patient.suivi-sante.*') ? 'bg-blue-800 text-white' : 'hover:bg-blue-800' }}">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
    </svg>
    <span x-show="sidebarOpen">Suivi Santé</span>
</a>


