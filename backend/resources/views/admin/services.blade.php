@extends('layouts.app')

@section('title', 'Services hospitaliers')
@section('page-title', 'Gestion des services')
@section('breadcrumb', 'Services')

@section('sidebar')
@include('admin.partials.sidebar')
@endsection

@section('content')
<div class="space-y-6">
    <!-- Messages de succès/erreur -->
    @if(session('success'))
    <div class="flex items-center gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm text-emerald-700 shadow-sm animate-fadeIn">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div>
            <p class="font-semibold">Succès</p>
            <p class="text-xs">{{ session('success') }}</p>
        </div>
    </div>
    @endif
    
    @if(session('error'))
    <div class="flex items-center gap-3 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm text-rose-700 shadow-sm">
        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 0a9 9 0 1-18 0 9 9 0 0118 0z"></path>
        </svg>
        <div>
            <p class="font-semibold">Erreur</p>
            <p class="text-xs">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Header avec gradient professionnel -->
    <div class="rounded-2xl bg-gradient-to-r from-purple-600 via-purple-700 to-purple-900 p-8 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="rounded-xl bg-white/20 p-3 backdrop-blur-sm">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">Services médicaux</h1>
                    <p class="text-purple-100 mt-1">Gérez les services et leurs praticiens associés</p>
                </div>
            </div>
            <button onclick="openCreateModal()" class="inline-flex items-center gap-2 rounded-xl bg-white/20 hover:bg-white/30 backdrop-blur-sm px-4 py-2 font-semibold transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Nouveau
            </button>
        </div>
    </div>

    <!-- Statistiques en cartes -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="rounded-xl border border-purple-200 bg-gradient-to-br from-purple-50 to-purple-100 p-5 shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-purple-600">Total services</p>
                    <p class="mt-2 text-3xl font-bold text-purple-700">{{ $services->count() }}</p>
                    <p class="mt-1 text-xs text-purple-500">Services enregistrés</p>
                </div>
                <div class="rounded-lg bg-purple-600 p-3 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-indigo-200 bg-gradient-to-br from-indigo-50 to-indigo-100 p-5 shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-indigo-600">Praticiens</p>
                    <p class="mt-2 text-3xl font-bold text-indigo-700">{{ $services->sum(fn($s) => $s->praticiens->count()) }}</p>
                    <p class="mt-1 text-xs text-indigo-500">Associés aux services</p>
                </div>
                <div class="rounded-lg bg-indigo-600 p-3 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-pink-200 bg-gradient-to-br from-pink-50 to-pink-100 p-5 shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-pink-600">Moyenne</p>
                    <p class="mt-2 text-3xl font-bold text-pink-700">{{ $services->count() > 0 ? round($services->sum(fn($s) => $s->praticiens->count()) / $services->count(), 1) : 0 }}</p>
                    <p class="mt-1 text-xs text-pink-500">Praticiens par service</p>
                </div>
                <div class="rounded-lg bg-pink-600 p-3 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Table professionnelle -->
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gradient-to-r from-purple-600 to-purple-800 text-xs uppercase tracking-wider text-white">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold">Libellé du service</th>
                        <th class="px-6 py-4 text-left font-semibold">Description</th>
                        <th class="px-6 py-4 text-left font-semibold">Praticiens</th>
                        <th class="px-6 py-4 text-left font-semibold">Créé le</th>
                        <th class="px-6 py-4 text-center font-semibold">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($services as $service)
                        <tr class="hover:bg-purple-50/50 transition-colors duration-200 border-l-4 border-l-purple-400">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-gradient-to-br from-purple-400 to-purple-600 text-white font-semibold text-sm">
                                        {{ strtoupper(substr($service->nom, 0, 1)) }}
                                    </div>
                                    <div class="font-semibold text-gray-900">{{ $service->nom }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-gray-600 text-sm max-w-xs truncate">{{ $service->description ?? '—' }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <div class="space-y-2">
                                    <span class="inline-flex items-center rounded-full bg-gradient-to-r from-indigo-100 to-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700 border border-indigo-200">
                                        <span class="inline-block h-2 w-2 rounded-full bg-indigo-600 mr-2"></span>
                                        {{ $service->praticiens->count() }} praticien(s)
                                    </span>
                                    @if($service->praticiens->count() > 0)
                                        <div class="flex flex-wrap gap-1">
                                            @foreach($service->praticiens as $praticien)
                                                <span class="inline-flex items-center rounded-lg bg-gray-100 px-2 py-1 text-xs text-gray-700 border border-gray-200">
                                                    {{ optional($praticien->user)->nom_complet }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600 text-sm">
                                <span class="inline-flex items-center rounded-lg bg-gray-100 px-3 py-1 font-medium">
                                    {{ optional($service->created_at)->format('d/m/Y') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <button onclick="confirmDelete({{ $service->id }}, '{{ $service->nom }}')" 
                                        class="inline-flex items-center justify-center rounded-lg p-2 text-rose-600 hover:bg-rose-100 transition-all duration-300 hover:text-rose-700 {{ $service->praticiens->count() > 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        title="{{ $service->praticiens->count() > 0 ? 'Impossible de supprimer un service avec des praticiens' : 'Supprimer' }}"
                                        @if($service->praticiens->count() > 0) disabled @endif>
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <p class="text-gray-500 text-lg font-medium">Aucun service enregistré</p>
                                    <p class="text-gray-400 text-sm">Commencez par créer un nouveau service</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal de création de service -->
<div id="createModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 backdrop-blur-sm">
    <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-md animate-slideInRight">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="rounded-lg bg-gradient-to-br from-purple-600 to-purple-800 p-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-gray-900">Nouveau service</h3>
            </div>
            <button onclick="closeCreateModal()" class="text-gray-400 hover:text-gray-600 transition p-1 hover:bg-gray-100 rounded-lg">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <form action="{{ route('admin.services.store') }}" method="POST" class="space-y-5">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-2">Nom du service *</label>
                <input type="text" name="nom" class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-purple-600 focus:ring-2 focus:ring-purple-200 transition-all" placeholder="Ex: Service de cardiologie" required>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-2">Localisation *</label>
                <input type="text" name="localisation" class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-purple-600 focus:ring-2 focus:ring-purple-200 transition-all" placeholder="Ex: Bâtiment A, 2ème étage" required>
            </div>
            
            <div>
                <label class="block text-sm font-semibold text-gray-900 mb-2">Description *</label>
                <textarea name="description" rows="3" class="w-full border-2 border-gray-200 rounded-lg px-4 py-2.5 focus:outline-none focus:border-purple-600 focus:ring-2 focus:ring-purple-200 transition-all resize-none" placeholder="Description du service..." required></textarea>
            </div>
            
            <div class="flex gap-3 pt-4">
                <button type="button" onclick="closeCreateModal()" class="flex-1 bg-gray-200 text-gray-700 px-4 py-2.5 rounded-lg hover:bg-gray-300 font-semibold transition-colors">
                    Annuler
                </button>
                <button type="submit" class="flex-1 bg-gradient-to-r from-purple-600 to-purple-700 text-white px-4 py-2.5 rounded-lg hover:from-purple-700 hover:to-purple-800 font-semibold transition-all duration-300 shadow-md hover:shadow-lg">
                    Créer le service
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Form de suppression (caché) -->
<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<script>
    function openCreateModal() {
        document.getElementById('createModal').classList.remove('hidden');
    }
    
    function closeCreateModal() {
        document.getElementById('createModal').classList.add('hidden');
    }
    
    function confirmDelete(serviceId, serviceName) {
        if (confirm(`Êtes-vous sûr de vouloir supprimer le service "${serviceName}" ?\n\nCette action est irréversible.`)) {
            const form = document.getElementById('deleteForm');
            form.action = `/admin/services/${serviceId}`;
            form.submit();
        }
    }
    
    // Fermer le modal en cliquant en dehors
    document.getElementById('createModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeCreateModal();
        }
    });
</script>
@endsection
