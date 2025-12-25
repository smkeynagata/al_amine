@extends('secretaire.layouts.app')

@section('title', 'Agenda Praticien')

@section('content')
@php
    $rdvTotal = $rendezVous->count();
    $rdvConfirmes = $rendezVous->where('statut', 'CONFIRME')->count();
    $rdvPasses = $rendezVous->filter(fn ($rdv) => optional($rdv->date_heure_rdv)->isPast())->count();
    $rdvAVenir = $rdvTotal - $rdvPasses;
    $rdvParJour = $rendezVous->groupBy(fn ($rdv) => optional($rdv->date_heure_rdv)->format('Y-m-d'));
@endphp

<div class="mb-8 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-2xl p-6 text-white shadow-xl flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
    <div>
        <div class="flex items-center gap-3 mb-2">
            <span class="text-3xl">🗓️</span>
            <h2 class="text-2xl font-bold">Agenda de Dr. {{ $praticien->user->nom_complet }}</h2>
        </div>
        <p class="text-purple-100">Suivi détaillé des rendez-vous à venir et passés</p>
    </div>
    <a href="{{ route('secretaire.agendas') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-xl text-sm font-semibold transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        Retour à la liste
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="lg:col-span-1 space-y-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 text-white rounded-2xl flex items-center justify-center font-bold text-2xl shadow-lg">
                    {{ substr($praticien->user->prenom, 0, 1) }}{{ substr($praticien->user->name, 0, 1) }}
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Praticien</p>
                    <h3 class="text-lg font-bold text-gray-900">Dr. {{ $praticien->user->nom_complet }}</h3>
                    <p class="text-sm text-gray-600">{{ optional($praticien->service)->nom }}</p>
                </div>
            </div>
            <div class="space-y-3 text-sm text-gray-600">
                <p class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2v2a2 2 0 01-2 2h-1v1a1 1 0 01-1 1h-2v-1h-1a2 2 0 01-2-2V9a2 2 0 012-2h1V6a1 1 0 011-1h2v1h1z" />
                    </svg>
                    {{ $praticien->user->email }}
                </p>
                <p class="flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    {{ $praticien->user->telephone ?? 'N/A' }}
                </p>
            </div>
            <div class="mt-4">
                <h4 class="text-xs font-semibold text-gray-500 mb-2">Spécialités</h4>
                <div class="flex flex-wrap gap-2">
                    @forelse($praticien->specialites as $specialite)
                        <span class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-semibold">{{ $specialite->nom }}</span>
                    @empty
                        <span class="text-xs text-gray-500">Aucune spécialité définie</span>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h4 class="text-sm font-semibold text-gray-800 mb-4">Statistiques</h4>
            <div class="space-y-3 text-sm">
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Rendez-vous planifiés</span>
                    <span class="font-semibold text-gray-900">{{ $rdvTotal }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Confirmés</span>
                    <span class="font-semibold text-green-600">{{ $rdvConfirmes }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">À venir</span>
                    <span class="font-semibold text-indigo-600">{{ $rdvAVenir }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-gray-500">Déjà passés</span>
                    <span class="font-semibold text-gray-700">{{ $rdvPasses }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-gray-900">Planning des rendez-vous</h3>
                <div class="text-sm text-gray-500">Semaine courante et suivantes</div>
            </div>

            @if($rendezVous->isEmpty())
                <div class="text-center py-16">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    <p class="text-gray-500">Aucun rendez-vous planifié pour cette période.</p>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($rdvParJour as $date => $rdvJour)
                        @php($dateObj = \Carbon\Carbon::parse($date))
                        <div>
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center font-semibold">
                                    {{ $dateObj->locale('fr')->isoFormat('dd') }}
                                </div>
                                <div>
                                    <p class="text-xs uppercase text-gray-500">{{ $dateObj->locale('fr')->isoFormat('MMMM YYYY') }}</p>
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $dateObj->locale('fr')->isoFormat('dddd D MMMM') }}</h4>
                                </div>
                            </div>
                            <div class="space-y-4">
                                @foreach($rdvJour as $rdv)
                                    <div class="border border-gray-100 rounded-xl p-4 hover:border-indigo-200 hover:shadow-md transition">
                                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-500 text-white rounded-xl flex items-center justify-center font-semibold">
                                                    {{ substr($rdv->patient->user->prenom, 0, 1) }}{{ substr($rdv->patient->user->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <p class="text-xs uppercase text-gray-500">Patient</p>
                                                    <h5 class="font-semibold text-gray-900">{{ $rdv->patient->user->nom_complet }}</h5>
                                                    <p class="text-xs text-gray-500">
                                                        Durée : {{ $rdv->duree }} min
                                                        @if($rdv->consultation)
                                                            • Consultation enregistrée
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex flex-wrap items-center gap-3 text-sm">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 font-semibold">
                                                    {{ optional($rdv->date_heure_rdv)->format('H\hi') }}
                                                </span>
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                                    {{ $rdv->statut === 'CONFIRME' ? 'bg-green-100 text-green-800' :
                                                       ($rdv->statut === 'ANNULE' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                                    {{ $rdv->statut ?? 'PLANIFIE' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex flex-col gap-2 mt-4">
                                            <div class="flex flex-wrap items-center gap-2">
                                                @if(!in_array($rdv->statut, ['CONFIRME', 'TERMINE', 'ANNULE', 'ABSENT']))
                                                    <form method="POST" action="{{ route('secretaire.rendezvous.confirmer', $rdv) }}">
                                                        @csrf
                                                        <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-green-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-green-700"
                                                            onclick="return window.handleSecretaryConfirmRdv();">
                                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                            </svg>
                                                            Confirmer
                                                        </button>
                                                    </form>
                                                @endif

                                                @if(!in_array($rdv->statut, ['ANNULE', 'TERMINE']))
                                                    <form method="POST" action="{{ route('secretaire.rendezvous.annuler', $rdv) }}">
                                                        @csrf
                                                        <input type="hidden" name="motif" value="">
                                                        <button type="submit" class="inline-flex items-center gap-1 rounded-lg bg-red-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm transition hover:bg-red-700"
                                                            onclick="return window.handleSecretaryCancelRdv(this);">
                                                            <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                            </svg>
                                                            Annuler
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                            @if($rdv->notes)
                                                <p class="text-xs text-gray-500">Motif : <span class="font-medium text-gray-700">{{ $rdv->notes }}</span></p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    window.handleSecretaryConfirmRdv = function () {
        return window.confirm('Confirmer ce rendez-vous ?');
    };

    window.handleSecretaryCancelRdv = function (button) {
        const reason = window.prompt('Motif de l\'annulation (optionnel) :', '');
        if (reason === null) {
            return false;
        }

        const form = button.closest('form');
        if (!form) {
            return false;
        }

        const input = form.querySelector('input[name="motif"]');
        if (input) {
            input.value = reason.trim();
        }

        return true;
    };
</script>
@endpush
