@extends('layouts.app')

@section('title', 'Mon Dossier Médical')
@section('page-title', 'Dossier Médical')

@section('sidebar')
    @include('patient.partials.sidebar')
@endsection

@section('content')
<div class="space-y-6" x-data="{ activeTab: 'consultations' }">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-lg p-6 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold mb-2">Mon Dossier Médical Complet</h2>
                <p class="text-blue-100">Consultez l'historique de vos consultations, ordonnances, examens et documents médicaux</p>
            </div>
            <div class="bg-white/20 rounded-full p-4">
                <i class="fas fa-file-medical text-4xl"></i>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-lg shadow-md">
        <div class="border-b border-gray-200">
            <nav class="flex -mb-px overflow-x-auto">
                <button @click="activeTab = 'consultations'" 
                        :class="activeTab === 'consultations' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-stethoscope mr-2"></i>
                    Consultations ({{ $consultations->total() }})
                </button>
                <button @click="activeTab = 'ordonnances'" 
                        :class="activeTab === 'ordonnances' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-prescription mr-2"></i>
                    Ordonnances ({{ $ordonnances->count() }})
                </button>
                <button @click="activeTab = 'examens'" 
                        :class="activeTab === 'examens' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-vial mr-2"></i>
                    Examens ({{ $examens->count() }})
                </button>
                <button @click="activeTab = 'documents'" 
                        :class="activeTab === 'documents' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-folder-open mr-2"></i>
                    Documents ({{ $documents->count() }})
                </button>
                <button @click="activeTab = 'allergies'" 
                        :class="activeTab === 'allergies' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-4 px-6 border-b-2 font-medium text-sm transition-colors">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    Allergies & Antécédents
                </button>
            </nav>
        </div>

        <!-- Consultations Tab -->
        <div x-show="activeTab === 'consultations'" class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Historique des consultations</h3>
            
            @forelse($consultations as $consultation)
                <div class="border border-gray-200 rounded-lg p-4 mb-4 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-3 py-1 rounded-full">
                                    {{ $consultation->date_consultation->format('d/m/Y') }}
                                </span>
                                <span class="text-gray-600 text-sm">
                                    <i class="fas fa-clock mr-1"></i>
                                    {{ $consultation->heure_debut }}
                                </span>
                            </div>
                            <h4 class="font-semibold text-gray-900 mb-1">
                                Dr. {{ $consultation->praticien->user->nom_complet }}
                            </h4>
                            <p class="text-sm text-gray-600 mb-2">
                                <i class="fas fa-clinic-medical mr-1"></i>
                                {{ $consultation->praticien->specialites->pluck('nom')->implode(', ') }}
                            </p>
                            @if($consultation->motif)
                                <p class="text-sm text-gray-700">
                                    <strong>Motif:</strong> {{ Str::limit($consultation->motif, 100) }}
                                </p>
                            @endif
                        </div>
                        <a href="{{ route('patient.dossier-medical.consultation', $consultation) }}" 
                           class="ml-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-eye mr-1"></i>
                            Voir détails
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-stethoscope text-4xl mb-3 text-gray-300"></i>
                    <p>Aucune consultation enregistrée</p>
                </div>
            @endforelse

            <div class="mt-6">
                {{ $consultations->links() }}
            </div>
        </div>

        <!-- Ordonnances Tab -->
        <div x-show="activeTab === 'ordonnances'" class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Mes ordonnances</h3>
            
            @forelse($ordonnances as $ordonnance)
                <div class="border border-gray-200 rounded-lg p-4 mb-4 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">
                                    {{ $ordonnance->created_at->format('d/m/Y') }}
                                </span>
                            </div>
                            <h4 class="font-semibold text-gray-900 mb-1">
                                Ordonnance #{{ $ordonnance->id }}
                            </h4>
                            <p class="text-sm text-gray-600 mb-2">
                                Dr. {{ $ordonnance->praticien->user->nom_complet }}
                            </p>
                            @if($ordonnance->medicaments)
                                <p class="text-sm text-gray-700">
                                    <strong>Médicaments:</strong> {{ Str::limit($ordonnance->medicaments, 100) }}
                                </p>
                            @endif
                        </div>
                        <a href="{{ route('patient.dossier-medical.ordonnance.download', $ordonnance) }}" 
                           class="ml-4 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-download mr-1"></i>
                            Télécharger PDF
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-prescription text-4xl mb-3 text-gray-300"></i>
                    <p>Aucune ordonnance disponible</p>
                </div>
            @endforelse
        </div>

        <!-- Examens Tab -->
        <div x-show="activeTab === 'examens'" class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Résultats d'examens</h3>
            
            @forelse($examens as $examen)
                <div class="border border-gray-200 rounded-lg p-4 mb-4 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="bg-purple-100 text-purple-800 text-xs font-semibold px-3 py-1 rounded-full">
                                    {{ $examen->date_examen->format('d/m/Y') }}
                                </span>
                                @if($examen->statut === 'termine')
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        Terminé
                                    </span>
                                @elseif($examen->statut === 'en_attente')
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-3 py-1 rounded-full">
                                        <i class="fas fa-clock mr-1"></i>
                                        En attente
                                    </span>
                                @endif
                            </div>
                            <h4 class="font-semibold text-gray-900 mb-1">
                                {{ $examen->titre }}
                            </h4>
                            <p class="text-sm text-gray-600 mb-2">
                                <i class="fas fa-flask mr-1"></i>
                                {{ $examen->type_examen }}
                                @if($examen->laboratoire)
                                    - {{ $examen->laboratoire }}
                                @endif
                            </p>
                            @if($examen->description)
                                <p class="text-sm text-gray-700">
                                    {{ Str::limit($examen->description, 100) }}
                                </p>
                            @endif
                        </div>
                        @if($examen->fichier_resultat)
                            <a href="{{ route('patient.dossier-medical.examen.download', $examen) }}" 
                               class="ml-4 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                                <i class="fas fa-download mr-1"></i>
                                Télécharger
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-vial text-4xl mb-3 text-gray-300"></i>
                    <p>Aucun examen médical enregistré</p>
                </div>
            @endforelse
        </div>

        <!-- Documents Tab -->
        <div x-show="activeTab === 'documents'" class="p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4">Mes documents médicaux</h3>
            
            @forelse($documents as $document)
                <div class="border border-gray-200 rounded-lg p-4 mb-4 hover:shadow-md transition-shadow">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-3 py-1 rounded-full">
                                    {{ $document->date_document->format('d/m/Y') }}
                                </span>
                                <span class="bg-gray-100 text-gray-700 text-xs font-semibold px-3 py-1 rounded-full">
                                    {{ str_replace('_', ' ', ucfirst($document->type_document)) }}
                                </span>
                            </div>
                            <h4 class="font-semibold text-gray-900 mb-1">
                                {{ $document->titre }}
                            </h4>
                            @if($document->delivre_par)
                                <p class="text-sm text-gray-600 mb-2">
                                    <i class="fas fa-user-md mr-1"></i>
                                    Délivré par: {{ $document->delivre_par }}
                                </p>
                            @endif
                            @if($document->description)
                                <p class="text-sm text-gray-700">
                                    {{ Str::limit($document->description, 100) }}
                                </p>
                            @endif
                        </div>
                        <a href="{{ route('patient.dossier-medical.document.download', $document) }}" 
                           class="ml-4 bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                            <i class="fas fa-download mr-1"></i>
                            Télécharger
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-gray-500">
                    <i class="fas fa-folder-open text-4xl mb-3 text-gray-300"></i>
                    <p>Aucun document médical disponible</p>
                </div>
            @endforelse
        </div>

        <!-- Allergies & Antécédents Tab -->
        <div x-show="activeTab === 'allergies'" class="p-6">
            <div class="max-w-3xl mx-auto">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Allergies et Antécédents Médicaux</h3>
                
                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                        <div class="text-sm text-yellow-800">
                            <p class="font-medium mb-1">Information importante</p>
                            <p>Ces informations sont cruciales pour votre sécurité. Elles seront consultées par vos praticiens avant chaque prescription.</p>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('patient.dossier-medical.allergies-antecedents.update') }}">
                    @csrf
                    @method('PATCH')

                    <div class="space-y-6">
                        <!-- Allergies -->
                        <div>
                            <label for="allergies" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-allergies text-red-600 mr-2"></i>
                                Allergies connues
                            </label>
                            <textarea 
                                name="allergies" 
                                id="allergies" 
                                rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Ex: Allergie à la pénicilline, allergie aux fruits de mer, allergie au pollen, etc."
                            >{{ old('allergies', auth()->user()->patient->allergies) }}</textarea>
                            @error('allergies')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Antécédents -->
                        <div>
                            <label for="antecedents" class="block text-sm font-medium text-gray-700 mb-2">
                                <i class="fas fa-history text-blue-600 mr-2"></i>
                                Antécédents médicaux et familiaux
                            </label>
                            <textarea 
                                name="antecedents" 
                                id="antecedents" 
                                rows="4"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Ex: Diabète familial, antécédent d'AVC, maladies cardiovasculaires, cancers, etc."
                            >{{ old('antecedents', auth()->user()->patient->antecedents) }}</textarea>
                            @error('antecedents')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button 
                                type="submit" 
                                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                                <i class="fas fa-save mr-2"></i>
                                Enregistrer
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)" 
     class="fixed bottom-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
    {{ session('success') }}
</div>
@endif
@endsection
