@extends('layouts.app')

@section('title', 'Journal d\'audit')
@section('page-title', 'Journal d\'audit')
@section('breadcrumb', 'Audit')

@section('sidebar')
@include('admin.partials.sidebar')
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header avec gradient professionnel -->
    <div class="rounded-2xl bg-gradient-to-r from-amber-600 via-amber-700 to-amber-900 p-8 text-white shadow-lg">
        <div class="flex items-center justify-between flex-col md:flex-row gap-6">
            <div class="flex items-center gap-4 flex-1">
                <div class="rounded-xl bg-white/20 p-3 backdrop-blur-sm">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">Journal d'audit</h1>
                    <p class="text-amber-100 mt-1">Suivi complet des opérations sensibles</p>
                </div>
            </div>
            <div class="inline-flex items-center gap-2 rounded-full bg-white/20 px-4 py-2 backdrop-blur-sm font-semibold text-sm">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8zm.5-13H11v6l5.25 3.15.75-1.23-4.5-2.67z"></path>
                </svg>
                <span>Activité en temps réel</span>
            </div>
        </div>
    </div>

    <!-- Barre de recherche professionelle -->
    <form method="GET" class="relative">
        <div class="flex gap-3">
            <div class="flex-1 relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher une action, un utilisateur, une adresse IP..." class="w-full rounded-lg border-2 border-gray-200 pl-11 pr-4 py-3 text-sm focus:outline-none focus:border-amber-600 focus:ring-2 focus:ring-amber-200 transition-all">
            </div>
            <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-amber-600 to-amber-700 px-6 py-3 text-sm font-bold text-white shadow-md hover:shadow-lg hover:from-amber-700 hover:to-amber-800 transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                Rechercher
            </button>
        </div>
    </form>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="rounded-xl border border-amber-200 bg-gradient-to-br from-amber-50 to-amber-100 p-5 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-amber-600">Total des actions</p>
                    <p class="mt-2 text-3xl font-bold text-amber-700">{{ $audits->total() }}</p>
                    <p class="mt-1 text-xs text-amber-500">Enregistrées</p>
                </div>
                <div class="rounded-lg bg-amber-600 p-3 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-orange-200 bg-gradient-to-br from-orange-50 to-orange-100 p-5 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-orange-600">Résultats actuels</p>
                    <p class="mt-2 text-3xl font-bold text-orange-700">{{ $audits->count() }}</p>
                    <p class="mt-1 text-xs text-orange-500">Affichés</p>
                </div>
                <div class="rounded-lg bg-orange-600 p-3 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-yellow-200 bg-gradient-to-br from-yellow-50 to-yellow-100 p-5 shadow-md">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-yellow-600">Pages de résultats</p>
                    <p class="mt-2 text-3xl font-bold text-yellow-700">{{ $audits->lastPage() }}</p>
                    <p class="mt-1 text-xs text-yellow-500">Au total</p>
                </div>
                <div class="rounded-lg bg-yellow-600 p-3 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Table professionnelle -->
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gradient-to-r from-amber-600 to-amber-800 text-xs uppercase tracking-wider text-white">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold">Date & Heure</th>
                        <th class="px-6 py-4 text-left font-semibold">Utilisateur</th>
                        <th class="px-6 py-4 text-left font-semibold">Action</th>
                        <th class="px-6 py-4 text-left font-semibold">Description</th>
                        <th class="px-6 py-4 text-left font-semibold">Adresse IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($audits as $audit)
                        <tr class="hover:bg-amber-50/50 transition-colors duration-200 border-l-4 border-l-amber-400">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="rounded-lg bg-amber-100 p-2">
                                        <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ $audit->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $audit->created_at->format('H:i:s') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-amber-400 to-amber-600 text-white font-semibold text-sm">
                                            {{ strtoupper(substr(optional($audit->user)->nom_complet ?? 'U', 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ optional($audit->user)->nom_complet ?? 'Utilisateur inconnu' }}</div>
                                        <div class="text-xs text-gray-500">{{ optional($audit->user)->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $actionColors = [
                                        'CREATE' => 'bg-emerald-100 text-emerald-700 border border-emerald-200',
                                        'UPDATE' => 'bg-blue-100 text-blue-700 border border-blue-200',
                                        'DELETE' => 'bg-rose-100 text-rose-700 border border-rose-200',
                                        'LOGIN' => 'bg-purple-100 text-purple-700 border border-purple-200',
                                        'LOGOUT' => 'bg-gray-100 text-gray-700 border border-gray-200',
                                        'VIEW' => 'bg-cyan-100 text-cyan-700 border border-cyan-200',
                                    ];
                                    $actionClass = $actionColors[$audit->action] ?? 'bg-gray-100 text-gray-700 border border-gray-200';
                                @endphp
                                <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-bold {{ $actionClass }}">
                                    <span class="inline-block h-2 w-2 rounded-full mr-2 {{ str_replace('text-', 'bg-', explode(' ', $actionClass)[2] ?? '') }}"></span>
                                    {{ $audit->action }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-700">
                                <span class="inline-flex items-center rounded-lg bg-gray-100 px-3 py-1 text-xs">
                                    {{ $audit->description ?? '—' }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <code class="text-xs bg-gray-100 px-2 py-1 rounded text-gray-700 font-mono">{{ $audit->ip_address ?? '—' }}</code>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    <p class="text-gray-500 text-lg font-medium">Aucune activité enregistrée</p>
                                    <p class="text-gray-400 text-sm">Essayez une autre recherche</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex items-center justify-between border-t border-gray-100 px-6 py-4 bg-gray-50">
            <span class="text-sm font-semibold text-gray-700">Affichage : <strong class="text-amber-600">{{ $audits->count() }}</strong> sur <strong class="text-amber-600">{{ $audits->total() }}</strong></span>
            <div class="flex gap-2">
                {{ $audits->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
