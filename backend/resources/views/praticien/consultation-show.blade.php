@extends('praticien.layouts.app')

@section('title', 'Consultation - ' . $rendezVous->patient->user->nom_complet)

@section('content')
<div class="mb-6">
    <a href="{{ route('praticien.dashboard') }}" class="text-blue-600 hover:text-blue-700 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Retour au tableau de bord
    </a>
</div>

<!-- Patient Info -->
<div class="bg-white rounded-2xl shadow-elegant p-6 mb-6">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-2xl font-bold text-gray-900">Informations du Patient</h3>
        <span class="px-4 py-2 bg-blue-100 text-blue-800 rounded-lg font-semibold">
            {{ $rendezVous->date_heure_rdv->format('d/m/Y à H:i') }}
        </span>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm text-gray-600 mb-1">Nom complet</p>
            <p class="text-lg font-bold text-gray-900">{{ $rendezVous->patient->user->nom_complet }}</p>
        </div>
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm text-gray-600 mb-1">Date de naissance</p>
            <p class="text-lg font-bold text-gray-900">{{ $rendezVous->patient->user->date_naissance?->format('d/m/Y') ?? 'N/A' }}</p>
            <p class="text-xs text-gray-500">{{ $rendezVous->patient->user->date_naissance?->age ?? 'N/A' }} ans</p>
        </div>
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm text-gray-600 mb-1">Sexe</p>
            <p class="text-lg font-bold text-gray-900">{{ $rendezVous->patient->user->sexe === 'M' ? 'Masculin' : 'Féminin' }}</p>
        </div>
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm text-gray-600 mb-1">Téléphone</p>
            <p class="text-lg font-bold text-gray-900">{{ $rendezVous->patient->user->telephone }}</p>
        </div>
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm text-gray-600 mb-1">Adresse</p>
            <p class="text-lg font-bold text-gray-900">{{ $rendezVous->patient->user->adresse ?? 'N/A' }}</p>
            <p class="text-xs text-gray-500">{{ $rendezVous->patient->user->quartier ?? '' }}, {{ $rendezVous->patient->user->ville ?? '' }}</p>
        </div>
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm text-gray-600 mb-1">Mutuelle</p>
            <p class="text-lg font-bold text-gray-900">{{ $rendezVous->patient->mutuelle ?? 'Aucune' }}</p>
        </div>
    </div>

    @if($rendezVous->patient->allergies || $rendezVous->patient->antecedents)
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        @if($rendezVous->patient->allergies)
        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <p class="text-sm font-bold text-red-800 mb-2">⚠️ Allergies connues</p>
            <p class="text-sm text-red-700">{{ $rendezVous->patient->allergies }}</p>
        </div>
        @endif
        @if($rendezVous->patient->antecedents)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
            <p class="text-sm font-bold text-yellow-800 mb-2">📋 Antécédents médicaux</p>
            <p class="text-sm text-yellow-700">{{ $rendezVous->patient->antecedents }}</p>
        </div>
        @endif
    </div>
    @endif
</div>

<!-- Consultation Form or Display -->
@if(!$rendezVous->consultation)
<div class="bg-white rounded-2xl shadow-elegant p-6">
    <h3 class="text-2xl font-bold text-gray-900 mb-6">Fiche de Consultation</h3>

    <form method="POST" action="{{ route('praticien.consultation.store', $rendezVous) }}">
        @csrf

        <div class="space-y-6">
            <!-- Motif -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Motif de consultation *</label>
                <textarea name="motif" required rows="3"
                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500"
                          placeholder="Ex: Fièvre, toux, douleurs...">{{ $rendezVous->motif ?? old('motif') }}</textarea>
                @error('motif')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Diagnostic -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Diagnostic *</label>
                <textarea name="diagnostic" required rows="4"
                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500"
                          placeholder="Diagnostic détaillé...">{{ old('diagnostic') }}</textarea>
                @error('diagnostic')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Traitement -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Traitement prescrit</label>
                <textarea name="traitement" rows="4"
                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500"
                          placeholder="Médicaments, posologie...">{{ old('traitement') }}</textarea>
                @error('traitement')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Examens complémentaires -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Examens complémentaires</label>
                <textarea name="examens_complementaires" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500"
                          placeholder="Analyses, radiographies...">{{ old('examens_complementaires') }}</textarea>
                @error('examens_complementaires')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Observations -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Observations</label>
                <textarea name="observations" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500"
                          placeholder="Notes supplémentaires...">{{ old('observations') }}</textarea>
                @error('observations')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Mesures de santé -->
            <div class="border-t pt-6 mt-6">
                <h4 class="text-lg font-bold text-gray-900 mb-4">📊 Mesures de santé</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Poids (kg)</label>
                        <input type="number" step="0.1" name="poids" value="{{ old('poids') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                               placeholder="70.5">
                        @error('poids')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Taille (cm)</label>
                        <input type="number" step="0.1" name="taille" value="{{ old('taille', $rendezVous->patient->taille) }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                               placeholder="170">
                        @error('taille')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Température (°C)</label>
                        <input type="number" step="0.1" name="temperature" value="{{ old('temperature') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                               placeholder="37.0">
                        @error('temperature')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tension systolique (mmHg)</label>
                        <input type="number" name="tension_systolique" value="{{ old('tension_systolique') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                               placeholder="120">
                        @error('tension_systolique')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tension diastolique (mmHg)</label>
                        <input type="number" name="tension_diastolique" value="{{ old('tension_diastolique') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                               placeholder="80">
                        @error('tension_diastolique')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Fréquence cardiaque (bpm)</label>
                        <input type="number" name="frequence_cardiaque" value="{{ old('frequence_cardiaque') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                               placeholder="70">
                        @error('frequence_cardiaque')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="md:col-span-3">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes sur les mesures</label>
                        <textarea name="notes_mesures" rows="2"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                                  placeholder="Observations sur les mesures prises...">{{ old('notes_mesures') }}</textarea>
                        @error('notes_mesures')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center mt-8 pt-6 border-t">
            <a href="{{ route('praticien.dashboard') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-900 px-6 py-3 rounded-lg font-semibold">
                Annuler
            </a>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Enregistrer la consultation
            </button>
        </div>
    </form>
</div>
@else
<!-- Consultation déjà effectuée -->
<div class="bg-white rounded-2xl shadow-elegant p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-2xl font-bold text-gray-900">Consultation effectuée</h3>
        <span class="px-4 py-2 bg-green-100 text-green-800 rounded-lg font-semibold">
            ✓ Terminée le {{ $rendezVous->consultation->date_consultation->format('d/m/Y à H:i') }}
        </span>
    </div>

    <div class="space-y-4">
        <div class="bg-blue-50 rounded-lg p-4">
            <p class="text-sm font-bold text-blue-900 mb-2">Motif de consultation</p>
            <p class="text-gray-700">{{ $rendezVous->consultation->motif }}</p>
        </div>

        <div class="bg-purple-50 rounded-lg p-4">
            <p class="text-sm font-bold text-purple-900 mb-2">Diagnostic</p>
            <p class="text-gray-700">{{ $rendezVous->consultation->diagnostic }}</p>
        </div>

        @if($rendezVous->consultation->traitement)
        <div class="bg-green-50 rounded-lg p-4">
            <p class="text-sm font-bold text-green-900 mb-2">Traitement prescrit</p>
            <p class="text-gray-700">{{ $rendezVous->consultation->traitement }}</p>
        </div>
        @endif

        @if($rendezVous->consultation->examens_complementaires)
        <div class="bg-yellow-50 rounded-lg p-4">
            <p class="text-sm font-bold text-yellow-900 mb-2">Examens complémentaires</p>
            <p class="text-gray-700">{{ $rendezVous->consultation->examens_complementaires }}</p>
        </div>
        @endif

        @if($rendezVous->consultation->observations)
        <div class="bg-gray-50 rounded-lg p-4">
            <p class="text-sm font-bold text-gray-900 mb-2">Observations</p>
            <p class="text-gray-700">{{ $rendezVous->consultation->observations }}</p>
        </div>
        @endif
    </div>

    @php
        $mesure = \App\Models\MesureSante::where('patient_id', $rendezVous->patient_id)
            ->whereDate('date_mesure', $rendezVous->consultation->date_consultation->toDateString())
            ->first();
    @endphp

    @if($mesure)
    <div class="mt-6 border-t pt-6">
        <h4 class="text-lg font-bold text-gray-900 mb-4">📊 Mesures de santé enregistrées</h4>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @if($mesure->poids)
            <div class="bg-blue-50 rounded-lg p-3">
                <p class="text-xs text-blue-600 mb-1">Poids</p>
                <p class="text-lg font-bold text-blue-900">{{ $mesure->poids }} kg</p>
                @if($mesure->imc)
                <p class="text-xs text-blue-600">IMC: {{ $mesure->imc }}</p>
                @endif
            </div>
            @endif
            @if($mesure->taille)
            <div class="bg-green-50 rounded-lg p-3">
                <p class="text-xs text-green-600 mb-1">Taille</p>
                <p class="text-lg font-bold text-green-900">{{ $mesure->taille }} cm</p>
            </div>
            @endif
            @if($mesure->temperature)
            <div class="bg-orange-50 rounded-lg p-3">
                <p class="text-xs text-orange-600 mb-1">Température</p>
                <p class="text-lg font-bold text-orange-900">{{ $mesure->temperature }}°C</p>
            </div>
            @endif
            @if($mesure->tension_systolique && $mesure->tension_diastolique)
            <div class="bg-red-50 rounded-lg p-3">
                <p class="text-xs text-red-600 mb-1">Tension</p>
                <p class="text-lg font-bold text-red-900">{{ $mesure->tension_systolique }}/{{ $mesure->tension_diastolique }}</p>
            </div>
            @endif
            @if($mesure->frequence_cardiaque)
            <div class="bg-purple-50 rounded-lg p-3">
                <p class="text-xs text-purple-600 mb-1">Fréquence cardiaque</p>
                <p class="text-lg font-bold text-purple-900">{{ $mesure->frequence_cardiaque }} bpm</p>
            </div>
            @endif
        </div>
        @if($mesure->notes)
        <div class="mt-4 bg-gray-50 rounded-lg p-3">
            <p class="text-xs text-gray-600 mb-1">Notes sur les mesures</p>
            <p class="text-sm text-gray-700">{{ $mesure->notes }}</p>
        </div>
        @endif
    </div>
    @endif

    <div class="flex justify-end mt-6">
        <a href="{{ route('praticien.ordonnance', $rendezVous->consultation) }}" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-3 rounded-lg font-semibold">
            📋 Créer/Voir l'ordonnance
        </a>
    </div>
</div>
@endif
@endsection

