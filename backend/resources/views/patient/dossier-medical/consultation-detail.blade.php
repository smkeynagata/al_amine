@extends('layouts.app')

@section('title', 'Détail Consultation')
@section('page-title', 'Détail de la Consultation')

@section('sidebar')
    @include('patient.partials.sidebar')
@endsection

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('patient.dossier-medical') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour au dossier médical
        </a>
    </div>

    <!-- Consultation Header -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-start justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Consultation du {{ $consultation->date_consultation->format('d/m/Y') }}</h2>
                <p class="text-gray-600">
                    <i class="fas fa-clock mr-2"></i>
                    {{ $consultation->heure_debut }} - {{ $consultation->heure_fin }}
                </p>
            </div>
            <span class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-sm font-semibold">
                Consultation #{{ $consultation->id }}
            </span>
        </div>

        <!-- Praticien Info -->
        <div class="border-t border-gray-200 pt-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">
                <i class="fas fa-user-md mr-2 text-blue-600"></i>
                Praticien
            </h3>
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 text-xl font-bold">
                    {{ substr($consultation->praticien->user->prenom, 0, 1) }}{{ substr($consultation->praticien->user->name, 0, 1) }}
                </div>
                <div>
                    <p class="font-semibold text-gray-900">Dr. {{ $consultation->praticien->user->nom_complet }}</p>
                    <p class="text-sm text-gray-600">
                        {{ $consultation->praticien->specialites->pluck('nom')->implode(', ') }}
                    </p>
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-phone mr-1"></i>
                        {{ $consultation->praticien->user->telephone }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Motif -->
        @if($consultation->motif)
        <div class="border-t border-gray-200 pt-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">
                <i class="fas fa-comment-medical mr-2 text-blue-600"></i>
                Motif de consultation
            </h3>
            <p class="text-gray-700 bg-gray-50 p-4 rounded-lg">{{ $consultation->motif }}</p>
        </div>
        @endif

        <!-- Diagnostic -->
        @if($consultation->diagnostic)
        <div class="border-t border-gray-200 pt-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">
                <i class="fas fa-diagnoses mr-2 text-blue-600"></i>
                Diagnostic
            </h3>
            <p class="text-gray-700 bg-blue-50 p-4 rounded-lg">{{ $consultation->diagnostic }}</p>
        </div>
        @endif

        <!-- Observations -->
        @if($consultation->observations)
        <div class="border-t border-gray-200 pt-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">
                <i class="fas fa-notes-medical mr-2 text-blue-600"></i>
                Observations
            </h3>
            <p class="text-gray-700 bg-gray-50 p-4 rounded-lg">{{ $consultation->observations }}</p>
        </div>
        @endif

        <!-- Traitement -->
        @if($consultation->traitement)
        <div class="border-t border-gray-200 pt-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">
                <i class="fas fa-pills mr-2 text-blue-600"></i>
                Traitement prescrit
            </h3>
            <p class="text-gray-700 bg-green-50 p-4 rounded-lg">{{ $consultation->traitement }}</p>
        </div>
        @endif

        <!-- Recommandations -->
        @if($consultation->recommandations)
        <div class="border-t border-gray-200 pt-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-3">
                <i class="fas fa-lightbulb mr-2 text-yellow-600"></i>
                Recommandations
            </h3>
            <p class="text-gray-700 bg-yellow-50 p-4 rounded-lg">{{ $consultation->recommandations }}</p>
        </div>
        @endif
    </div>

    <!-- Related Documents -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">
            <i class="fas fa-paperclip mr-2 text-blue-600"></i>
            Documents liés
        </h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @if($consultation->ordonnance)
                <div class="border border-green-200 bg-green-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">Ordonnance</p>
                            <p class="text-sm text-gray-600">Prescription médicale</p>
                        </div>
                        <a href="{{ route('patient.dossier-medical.ordonnance.download', $consultation->ordonnance) }}" 
                           class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                            <i class="fas fa-download"></i>
                        </a>
                    </div>
                </div>
            @endif

            @forelse($consultation->examens as $examen)
                <div class="border border-purple-200 bg-purple-50 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="font-medium text-gray-900">{{ $examen->titre }}</p>
                            <p class="text-sm text-gray-600">{{ $examen->type_examen }}</p>
                        </div>
                        @if($examen->fichier_resultat)
                            <a href="{{ route('patient.dossier-medical.examen.download', $examen) }}" 
                               class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm">
                                <i class="fas fa-download"></i>
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-8 text-gray-500">
                    <i class="fas fa-file-medical text-3xl mb-2 text-gray-300"></i>
                    <p>Aucun document supplémentaire</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
