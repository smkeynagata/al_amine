@extends('layouts.app')

@section('title', 'Mes rendez-vous')
@section('page-title', 'Mes rendez-vous')
@section('breadcrumb', "Patient > Mes RDV")

@section('sidebar')
@include('patient.partials.sidebar')
@endsection

@section('content')
@php
    $rdvCollection = method_exists($rendezVous, 'items')
        ? collect($rendezVous->items())
        : collect($rendezVous);
    $rdvFuturs = $rdvCollection->filter(fn($rdv) => optional($rdv->date_heure_rdv)->isFuture() && $rdv->statut !== 'ANNULE');
    $rdvPasses = $rdvCollection->filter(fn($rdv) => optional($rdv->date_heure_rdv)->isPast() || $rdv->statut === 'ANNULE');
@endphp

@if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-lg flex items-center">
    <i class="fas fa-check-circle mr-3 text-xl"></i>
    <span>{{ session('success') }}</span>
</div>
@endif

@if(session('error'))
<div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-lg flex items-center">
    <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
    <span>{{ session('error') }}</span>
</div>
@endif

<div class="mb-6 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
    <div>
        <h3 class="text-xl font-semibold text-gray-800">Suivi de vos rendez-vous</h3>
        <p class="text-sm text-gray-500">Consultez vos prochains rendez-vous, votre historique et les consultations terminées.</p>
    </div>
    <a href="{{ route('patient.demander-rdv') }}" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-700">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Nouveau rendez-vous
    </a>
</div>

<div x-data="{ tab: 'futurs' }">
    <div class="mb-4 flex rounded-2xl border border-gray-200 bg-white p-1 shadow">
        <button type="button" :class="tab === 'futurs' ? 'bg-blue-600 text-white shadow' : 'text-gray-600'" @click="tab = 'futurs'"
            class="flex-1 rounded-xl px-4 py-2 text-sm font-semibold transition">
            📅 Rendez-vous à venir
        </button>
        <button type="button" :class="tab === 'passes' ? 'bg-blue-600 text-white shadow' : 'text-gray-600'" @click="tab = 'passes'"
            class="flex-1 rounded-xl px-4 py-2 text-sm font-semibold transition">
            📋 Historique & annulations
        </button>
    </div>

    <div x-show="tab === 'futurs'" x-cloak class="space-y-4">
        @forelse($rdvFuturs->sortBy('date_heure_rdv') as $rdv)
            <div class="rounded-2xl border border-blue-100 bg-blue-50/60 p-5 shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase text-blue-500">{{ $rdv->date_heure_rdv?->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</p>
                        <h4 class="text-lg font-semibold text-gray-900">Dr. {{ $rdv->praticien->user->nom_complet }}</h4>
                        <p class="text-xs text-gray-600">
                            {{ $rdv->praticien->specialites->pluck('nom')->implode(', ') ?: 'Spécialité non précisée' }}
                        </p>
                    </div>
                    <div class="flex flex-col items-end gap-2 text-sm">
                        <span class="inline-flex items-center gap-2 rounded-full bg-blue-100 px-3 py-1 text-blue-700">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
                            </svg>
                            {{ $rdv->date_heure_rdv?->format('H:i') }}
                        </span>
                        <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 text-gray-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                            </svg>
                            {{ $rdv->duree_minutes ?: 30 }} min
                        </span>
                        <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 text-gray-600">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.828 0l-4.243-4.243a8 8 0 1111.314 0z" />
                            </svg>
                            {{ $rdv->praticien->numero_bureau ?? 'Bureau à confirmer' }}
                        </span>
                    </div>
                </div>

                @if(filled($rdv->notes))
                    <p class="mt-3 rounded-xl bg-white/80 px-4 py-3 text-xs text-gray-600">{{ $rdv->notes }}</p>
                @endif

                <div class="mt-4 flex flex-wrap items-center justify-between gap-3 text-xs text-gray-500">
                    <span>{{ $rdv->date_heure_rdv?->diffForHumans() }}</span>
                    <div class="flex items-center gap-2">
                        <span class="inline-flex items-center gap-1 rounded-full bg-blue-600 px-3 py-1 text-xs font-semibold text-white">
                            {{ $rdv->statut_display }}
                        </span>
                        <button type="button" @click="$dispatch('open-patient-rdv', {{ json_encode(['id' => $rdv->id]) }})"
                            class="inline-flex items-center gap-1 rounded-full border border-blue-200 px-3 py-1 text-xs font-semibold text-blue-600 hover:bg-blue-100">
                            Détails
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-dashed border-blue-200 bg-white p-10 text-center text-sm text-gray-500">
                Aucun rendez-vous à venir. <a href="{{ route('patient.demander-rdv') }}" class="font-semibold text-blue-600">Planifiez votre prochaine visite</a>.
            </div>
        @endforelse
    </div>

    <div x-show="tab === 'passes'" x-cloak class="space-y-4">
        @forelse($rdvPasses->sortByDesc('date_heure_rdv') as $rdv)
            <div class="rounded-2xl border border-gray-200 bg-white p-5 shadow-sm">
                <div class="flex flex-wrap items-start justify-between gap-4">
                    <div>
                        <p class="text-xs uppercase text-gray-400">{{ $rdv->date_heure_rdv?->format('d/m/Y') }}</p>
                        <h4 class="text-lg font-semibold text-gray-900">Dr. {{ $rdv->praticien->user->nom_complet }}</h4>
                        <p class="text-xs text-gray-500">{{ $rdv->praticien->specialites->pluck('nom')->implode(', ') ?: 'Spécialité non précisée' }}</p>
                    </div>
                    <div class="flex items-center gap-2 text-xs">
                        <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 font-semibold
                            {{ $rdv->statut === 'ANNULE' ? 'bg-red-100 text-red-700' : ($rdv->statut === 'TERMINE' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600') }}">
                            {{ $rdv->statut_display }}
                        </span>
                        <span class="inline-flex items-center gap-1 rounded-full bg-gray-100 px-3 py-1 text-gray-600">
                            {{ $rdv->date_heure_rdv?->format('H:i') }}
                        </span>
                    </div>
                </div>

                @if($rdv->consultation)
                    <div class="mt-4 rounded-2xl border border-green-100 bg-green-50 px-4 py-3 text-xs text-green-700">
                        <p class="font-semibold">Consultation enregistrée</p>
                        <p class="mt-1">Diagnostic : {{ Str::limit($rdv->consultation->diagnostic, 120) ?? 'Non communiqué' }}</p>
                    </div>
                @endif

                <div class="mt-4 flex items-center justify-between text-xs text-gray-500">
                    <span>{{ $rdv->date_heure_rdv?->diffForHumans() }}</span>
                    @if($rdv->consultation)
                        <a href="{{ route('patient.mes-rdv') }}" class="text-blue-600 hover:text-blue-700">Consulter le compte-rendu →</a>
                    @endif
                </div>
            </div>
        @empty
            <div class="rounded-2xl border border-dashed border-gray-200 bg-white p-10 text-center text-sm text-gray-500">
                Aucun rendez-vous passé ou annulé sur la période consultée.
            </div>
        @endforelse
    </div>
</div>

@if(method_exists($rendezVous, 'links'))
    <div class="mt-6">
        {{ $rendezVous->links() }}
    </div>
@endif

<template x-teleport="body">
    <div x-data="patientRdvModal()" x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 px-4">
        <div @click.away="close" class="w-full max-w-lg rounded-3xl bg-white p-6 shadow-xl">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Détails du rendez-vous</h3>
                <button type="button" @click="close" class="rounded-full bg-gray-100 p-2 text-gray-500 hover:bg-gray-200">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            <div class="mt-4 space-y-3 text-sm text-gray-600" x-show="rdv">
                <p><span class="font-semibold">Praticien :</span> <span x-text="rdv.praticien"></span></p>
                <template x-if="rdv.specialites && rdv.specialites.length">
                    <p><span class="font-semibold">Spécialités :</span> <span x-text="rdv.specialites.join(', ')"></span></p>
                </template>
                <p><span class="font-semibold">Date :</span> <span x-text="rdv.date"></span></p>
                <p><span class="font-semibold">Heure :</span> <span x-text="rdv.heure"></span></p>
                <p><span class="font-semibold">Durée :</span> <span x-text="rdv.duree_formatee || (rdv.duree ? rdv.duree + ' min' : 'Non précisée')"></span></p>
                <template x-if="rdv.lieu">
                    <p><span class="font-semibold">Salle/Bureau :</span> <span x-text="rdv.lieu"></span></p>
                </template>
                <p><span class="font-semibold">Statut :</span> <span x-text="rdv.statut"></span></p>
                <template x-if="rdv.motif">
                    <p><span class="font-semibold">Motif :</span> <span x-text="rdv.motif"></span></p>
                </template>
                <template x-if="rdv.notes">
                    <p><span class="font-semibold">Notes :</span> <span x-text="rdv.notes"></span></p>
                </template>
                <template x-if="rdv.consultation">
                    <div class="rounded-2xl border border-green-100 bg-green-50 px-4 py-3 text-xs text-green-700">
                        <p class="font-semibold">Consultation enregistrée</p>
                        <p class="mt-1"><span class="font-semibold">Diagnostic :</span> <span x-text="rdv.consultation.diagnostic || 'Non communiqué'"></span></p>
                        <template x-if="rdv.consultation.traitement">
                            <p class="mt-1"><span class="font-semibold">Traitement :</span> <span x-text="rdv.consultation.traitement"></span></p>
                        </template>
                    </div>
                </template>
            </div>

            <div class="mt-6 flex justify-end">
                <button type="button" @click="close" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white hover:bg-blue-700">Fermer</button>
            </div>
        </div>
    </div>
</template>
@endsection

@push('scripts')
<script>
    window.patientRdvModal = function () {
        return {
            open: false,
            rdv: null,
            close() {
                this.open = false;
                this.rdv = null;
            },
            show(payload) {
                this.rdv = payload;
                this.open = true;
            },
        };
    };

    document.addEventListener('open-patient-rdv', (event) => {
        const id = event.detail?.id;
        if (!id) {
            return;
        }

        fetch(`{{ url('patient/rendez-vous') }}/${id}`, {
            headers: {
                'Accept': 'application/json',
            },
        })
            .then(response => response.ok ? response.json() : Promise.reject(response))
            .then(data => {
                const modal = document.querySelector('[x-data="patientRdvModal()"]')?.__x?.$data;
                if (modal) {
                    modal.show(data);
                }
            })
            .catch(() => alert('Impossible de charger les détails du rendez-vous.'));
    });
</script>
@endpush
