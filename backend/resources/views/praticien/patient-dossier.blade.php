@extends('praticien.layouts.app')

@section('title', 'Dossier Médical - ' . $patient->user->nom_complet)

@section('content')
<!-- Breadcrumb -->
<div class="mb-6">
    <a href="{{ route('praticien.patients') }}" class="text-blue-600 hover:text-blue-700 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Retour à la liste des patients
    </a>
</div>

<!-- En-tête du patient -->
<div class="bg-gradient-to-r from-purple-600 via-blue-600 to-cyan-600 rounded-2xl p-8 text-white shadow-2xl mb-8">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-6">
            <div class="w-24 h-24 bg-white bg-opacity-20 backdrop-blur-sm rounded-2xl flex items-center justify-center text-4xl font-bold shadow-lg">
                {{ substr($patient->user->prenom, 0, 1) }}{{ substr($patient->user->name, 0, 1) }}
            </div>
            <div>
                <h1 class="text-4xl font-bold mb-2">{{ $patient->user->nom_complet }}</h1>
                <div class="flex items-center gap-4 text-purple-100">
                    @if($patient->user->date_naissance)
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        {{ $patient->user->date_naissance->age }} ans
                    </span>
                    @endif
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                        {{ $patient->user->sexe === 'M' ? 'Masculin' : 'Féminin' }}
                    </span>
                    <span class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        {{ $patient->user->telephone }}
                    </span>
                </div>
            </div>
        </div>
        <div class="text-right">
            <button onclick="window.print()" class="bg-white bg-opacity-20 hover:bg-opacity-30 backdrop-blur-sm px-6 py-3 rounded-xl font-semibold transition flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Imprimer dossier
            </button>
        </div>
    </div>
</div>

<!-- Statistiques -->
<div class="grid grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-lg border border-blue-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <span class="text-3xl font-bold text-blue-600">{{ $stats['total_consultations'] }}</span>
        </div>
        <p class="text-sm font-semibold text-gray-600">Consultations</p>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-lg border border-purple-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <span class="text-3xl font-bold text-purple-600">{{ $stats['total_ordonnances'] }}</span>
        </div>
        <p class="text-sm font-semibold text-gray-600">Ordonnances</p>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-lg border border-green-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <span class="text-sm font-bold text-green-600">
                @if($stats['derniere_consultation'])
                    {{ $stats['derniere_consultation']->format('d/m/Y') }}
                @else
                    N/A
                @endif
            </span>
        </div>
        <p class="text-sm font-semibold text-gray-600">Dernière visite</p>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-lg border border-orange-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <span class="text-sm font-bold text-orange-600">
                @if($stats['prochain_rdv'])
                    {{ $stats['prochain_rdv']->date_heure_rdv->format('d/m') }}
                @else
                    Aucun
                @endif
            </span>
        </div>
        <p class="text-sm font-semibold text-gray-600">Prochain RDV</p>
    </div>
</div>

<div class="grid grid-cols-3 gap-6">
    <!-- Colonne principale -->
    <div class="col-span-2 space-y-6">
        <!-- Informations médicales -->
        <div class="bg-white rounded-2xl p-6 shadow-lg">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Informations Médicales
            </h2>

            <div class="grid grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Numéro de Sécurité Sociale</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $patient->numero_securite_sociale ?? 'Non renseigné' }}</p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Mutuelle</p>
                    <p class="text-lg font-semibold text-gray-900">{{ $patient->mutuelle ?? 'Non renseignée' }}</p>
                </div>

                @if($patient->allergies)
                <div class="col-span-2 bg-red-50 border border-red-200 rounded-lg p-4">
                    <p class="text-sm font-bold text-red-800 mb-2">⚠️ Allergies</p>
                    <p class="text-sm text-gray-700">{{ $patient->allergies }}</p>
                </div>
                @endif

                @if($patient->antecedents)
                <div class="col-span-2 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                    <p class="text-sm font-bold text-yellow-800 mb-2">📋 Antécédents</p>
                    <p class="text-sm text-gray-700">{{ $patient->antecedents }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Historique des consultations -->
        <div class="bg-white rounded-2xl p-6 shadow-lg">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Historique des Consultations
            </h2>

            @if($consultations->isNotEmpty())
            <div class="space-y-4">
                @foreach($consultations as $consultation)
                <div class="border border-gray-200 rounded-xl p-5 hover:border-purple-300 hover:shadow-md transition">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="bg-purple-100 text-purple-800 text-xs font-bold px-3 py-1 rounded-full">
                                    {{ $consultation->date_consultation->format('d/m/Y') }}
                                </span>
                                @if($consultation->est_validee)
                                <span class="bg-green-100 text-green-800 text-xs font-bold px-3 py-1 rounded-full flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                    Validée
                                </span>
                                @endif
                            </div>
                            <p class="text-sm font-semibold text-gray-900 mb-1">{{ $consultation->motif }}</p>
                            @if($consultation->diagnostic)
                            <p class="text-sm text-gray-600 mb-2"><span class="font-semibold">Diagnostic:</span> {{ $consultation->diagnostic }}</p>
                            @endif
                            @if($consultation->notes)
                            <p class="text-sm text-gray-500 mb-2">{{ Str::limit($consultation->notes, 100) }}</p>
                            @endif
                            
                            @if($consultation->ordonnances->isNotEmpty())
                            <div class="mt-3 flex items-center gap-2">
                                <span class="text-xs font-semibold text-blue-600 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    {{ $consultation->ordonnances->count() }} ordonnance(s)
                                </span>
                            </div>
                            @endif
                        </div>
                        <a href="{{ route('praticien.ordonnance', $consultation) }}" class="px-4 py-2 bg-purple-600 text-white rounded-lg text-sm font-semibold hover:bg-purple-700 transition">
                            Voir détails
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-500">Aucune consultation enregistrée</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Colonne latérale -->
    <div class="space-y-6">
        <!-- Contact -->
        <div class="bg-white rounded-2xl p-6 shadow-lg">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                Contact
            </h3>

            <div class="space-y-3">
                <div class="flex items-center gap-3 text-sm">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    <span class="text-gray-700">{{ $patient->user->telephone }}</span>
                </div>

                <div class="flex items-center gap-3 text-sm">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <span class="text-gray-700">{{ $patient->user->email }}</span>
                </div>

                @if($patient->user->adresse)
                <div class="flex items-start gap-3 text-sm">
                    <svg class="w-5 h-5 text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    <span class="text-gray-700">{{ $patient->user->adresse }}</span>
                </div>
                @endif

                @if($patient->user->ville)
                <div class="flex items-center gap-3 text-sm">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    <span class="text-gray-700">{{ $patient->user->ville }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Rendez-vous à venir -->
        <div class="bg-white rounded-2xl p-6 shadow-lg">
            <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Rendez-vous
            </h3>

            @php
                $rdvFuturs = $rendezVous->where('date_heure_rdv', '>', now())->take(5);
            @endphp

            @if($rdvFuturs->isNotEmpty())
            <div class="space-y-3">
                @foreach($rdvFuturs as $rdv)
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 rounded-lg p-3">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-bold text-green-800">{{ $rdv->date_heure_rdv->format('d/m/Y') }}</span>
                        <span class="text-xs font-semibold text-green-700 bg-green-200 px-2 py-1 rounded-full">{{ $rdv->statut }}</span>
                    </div>
                    <p class="text-sm text-gray-700">
                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        {{ $rdv->date_heure_rdv->format('H:i') }}
                    </p>
                    @if($rdv->notes)
                    <p class="text-xs text-gray-600 mt-1">{{ $rdv->notes }}</p>
                    @endif
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-6">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <p class="text-sm text-gray-500">Aucun RDV prévu</p>
            </div>
            @endif
        </div>

        <!-- Actions rapides -->
        <div class="bg-gradient-to-br from-purple-600 to-blue-600 rounded-2xl p-6 text-white shadow-lg">
            <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                Actions Rapides
            </h3>

            <div class="space-y-2">
                <button class="w-full bg-white bg-opacity-20 hover:bg-opacity-30 backdrop-blur-sm px-4 py-3 rounded-xl text-sm font-semibold transition text-left flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Prendre RDV
                </button>

                <button class="w-full bg-white bg-opacity-20 hover:bg-opacity-30 backdrop-blur-sm px-4 py-3 rounded-xl text-sm font-semibold transition text-left flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Envoyer message
                </button>

                <button onclick="window.print()" class="w-full bg-white bg-opacity-20 hover:bg-opacity-30 backdrop-blur-sm px-4 py-3 rounded-xl text-sm font-semibold transition text-left flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Imprimer dossier
                </button>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
@media print {
    .no-print, aside, header, nav, button, .bg-gradient-to-br {
        display: none !important;
    }
    body {
        background: white;
    }
}
</style>
@endpush

@endsection
