@extends('layouts.app')

@section('title', 'Agendas globaux')
@section('page-title', 'Agendas globaux')
@section('breadcrumb', 'Agendas globaux')

@section('sidebar')
@include('admin.partials.sidebar')
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header avec gradient professionnel -->
    <div class="rounded-2xl bg-gradient-to-r from-teal-600 via-teal-700 to-teal-900 p-8 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="rounded-xl bg-white/20 p-3 backdrop-blur-sm">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">Calendrier des rendez-vous</h1>
                    <p class="text-teal-100 mt-1">Vue consolidée de tous les rendez-vous programmés</p>
                </div>
            </div>
            <div class="rounded-xl bg-white/20 px-4 py-2 backdrop-blur-sm font-semibold">{{ $rdv->count() }} rendez-vous</div>
        </div>
    </div>

    <!-- Statistiques en cartes -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="rounded-xl border border-teal-200 bg-gradient-to-br from-teal-50 to-teal-100 p-5 shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-teal-600">Total RDV</p>
                    <p class="mt-2 text-3xl font-bold text-teal-700">{{ $rdv->count() }}</p>
                    <p class="mt-1 text-xs text-teal-500">Sur la période</p>
                </div>
                <div class="rounded-lg bg-teal-600 p-3 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-emerald-200 bg-gradient-to-br from-emerald-50 to-emerald-100 p-5 shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-emerald-600">Confirmés</p>
                    <p class="mt-2 text-3xl font-bold text-emerald-700">{{ $rdv->where('statut', 'CONFIRME')->count() }}</p>
                    <p class="mt-1 text-xs text-emerald-500">RDV confirmés</p>
                </div>
                <div class="rounded-lg bg-emerald-600 p-3 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-amber-200 bg-gradient-to-br from-amber-50 to-amber-100 p-5 shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-amber-600">En attente</p>
                    <p class="mt-2 text-3xl font-bold text-amber-700">{{ $rdv->where('statut', 'EN_ATTENTE')->count() }}</p>
                    <p class="mt-1 text-xs text-amber-500">À valider</p>
                </div>
                <div class="rounded-lg bg-amber-600 p-3 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-rose-200 bg-gradient-to-br from-rose-50 to-rose-100 p-5 shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-rose-600">Annulés</p>
                    <p class="mt-2 text-3xl font-bold text-rose-700">{{ $rdv->where('statut', 'ANNULE')->count() }}</p>
                    <p class="mt-1 text-xs text-rose-500">RDV annulés</p>
                </div>
                <div class="rounded-lg bg-rose-600 p-3 text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Table professionnelle -->
    <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-lg">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gradient-to-r from-teal-600 to-teal-800 text-xs uppercase tracking-wider text-white">
                    <tr>
                        <th class="px-6 py-4 text-left font-semibold">Date & heure</th>
                        <th class="px-6 py-4 text-left font-semibold">Patient</th>
                        <th class="px-6 py-4 text-left font-semibold">Praticien & Service</th>
                        <th class="px-6 py-4 text-left font-semibold">Motif</th>
                        <th class="px-6 py-4 text-left font-semibold">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse($rdv as $element)
                        <tr class="hover:bg-teal-50/50 transition-colors duration-200 border-l-4 border-l-teal-400">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <div class="rounded-lg bg-teal-100 p-2">
                                        <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ optional($element->date_heure_rdv)->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ optional($element->date_heure_rdv)->format('H:i') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-teal-400 to-teal-600 text-white font-semibold text-sm">
                                            {{ strtoupper(substr(optional(optional($element->patient)->user)->nom_complet ?? 'P', 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ optional(optional($element->patient)->user)->nom_complet }}</div>
                                        <div class="text-xs text-gray-500">{{ optional(optional($element->patient)->user)->telephone }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex-shrink-0">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 text-white font-semibold text-sm">
                                            {{ strtoupper(substr(optional(optional($element->praticien)->user)->nom_complet ?? 'P', 0, 1)) }}
                                        </div>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900">{{ optional(optional($element->praticien)->user)->nom_complet }}</div>
                                        <div class="text-xs text-gray-500">{{ optional(optional($element->praticien)->service)->libelle ?? 'Service général' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center rounded-lg bg-gray-100 px-3 py-1 text-sm font-medium text-gray-700">{{ $element->motif ?? '—' }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusConfig = [
                                        'CONFIRME' => ['badge' => 'bg-emerald-100 text-emerald-700 border border-emerald-200', 'icon' => '✓'],
                                        'EN_ATTENTE' => ['badge' => 'bg-amber-100 text-amber-700 border border-amber-200', 'icon' => '⏱'],
                                        'ANNULE' => ['badge' => 'bg-rose-100 text-rose-700 border border-rose-200', 'icon' => '✕'],
                                        'TERMINE' => ['badge' => 'bg-teal-100 text-teal-700 border border-teal-200', 'icon' => '✔'],
                                    ];
                                    $config = $statusConfig[$element->statut] ?? ['badge' => 'bg-gray-100 text-gray-600 border border-gray-200', 'icon' => '•'];
                                @endphp
                                <span class="inline-flex items-center gap-2 rounded-full px-3 py-1 text-xs font-semibold {{ $config['badge'] }}">
                                    <span class="inline-flex h-2 w-2 rounded-full {{ str_replace('bg-', 'bg-', explode(' ', $config['badge'])[0]) }}"></span>
                                    {{ ucfirst(strtolower($element->statut)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="text-gray-500 text-lg font-medium">Aucun rendez-vous programmé</p>
                                    <p class="text-gray-400 text-sm">Aucune donnée sur la période sélectionnée</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
