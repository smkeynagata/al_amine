@extends('layouts.app')

@section('title', 'Mes Documents')
@section('page-title', 'Mes Documents Médicaux')

@section('sidebar')
    @include('patient.partials.sidebar')
@endsection

@section('content')
<!-- Statistiques -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Documents</p>
                <p class="text-2xl font-bold text-blue-600">{{ $stats['total_documents'] }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-file-alt text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Certificats</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['certificats'] }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-between">
                <i class="fas fa-certificate text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Ordonnances</p>
                <p class="text-2xl font-bold text-purple-600">{{ $stats['ordonnances'] }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-prescription text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Examens</p>
                <p class="text-2xl font-bold text-orange-600">{{ $stats['examens'] }}</p>
            </div>
            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-flask text-orange-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Actions rapides -->
<div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-md p-6 mb-6 text-white">
    <h3 class="text-lg font-semibold mb-4">
        <i class="fas fa-plus-circle mr-2"></i>
        Générer un nouveau document
    </h3>
    <div class="flex flex-wrap gap-3">
        <button onclick="openCertificatModal()" class="bg-white text-blue-600 px-4 py-2 rounded-lg font-medium hover:bg-blue-50 transition-colors flex items-center">
            <i class="fas fa-file-medical mr-2"></i>
            Certificat médical
        </button>
        <a href="{{ route('patient.dossier-medical') }}" class="bg-white text-blue-600 px-4 py-2 rounded-lg font-medium hover:bg-blue-50 transition-colors flex items-center">
            <i class="fas fa-file-prescription mr-2"></i>
            Voir mes ordonnances
        </a>
    </div>
</div>

<!-- Filtres et recherche -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6" x-data="{ activeTab: 'tous' }">
    <div class="flex flex-wrap items-center justify-between mb-4">
        <div class="flex space-x-2 mb-3 md:mb-0">
            <button @click="activeTab = 'tous'" :class="activeTab === 'tous' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'" class="px-4 py-2 rounded-lg font-medium transition-colors">
                Tous
            </button>
            <button @click="activeTab = 'documents'" :class="activeTab === 'documents' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'" class="px-4 py-2 rounded-lg font-medium transition-colors">
                Documents
            </button>
            <button @click="activeTab = 'ordonnances'" :class="activeTab === 'ordonnances' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'" class="px-4 py-2 rounded-lg font-medium transition-colors">
                Ordonnances
            </button>
            <button @click="activeTab = 'examens'" :class="activeTab === 'examens' ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-700 hover:bg-gray-300'" class="px-4 py-2 rounded-lg font-medium transition-colors">
                Examens
            </button>
        </div>

        <form method="GET" action="{{ route('patient.documents.index') }}" class="flex items-center space-x-2">
            <input type="text" name="search" placeholder="Rechercher..." value="{{ request('search') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                <i class="fas fa-search"></i>
            </button>
        </form>
    </div>

    <!-- Documents médicaux -->
    <div x-show="activeTab === 'tous' || activeTab === 'documents'">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">
            <i class="fas fa-folder-open text-blue-600 mr-2"></i>
            Documents médicaux
        </h4>
        
        @if($documents->isEmpty())
            <div class="text-center py-12">
                <i class="fas fa-file-alt text-gray-300 text-5xl mb-3"></i>
                <p class="text-gray-500">Aucun document disponible</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($documents as $document)
                    <div class="bg-gray-50 rounded-lg p-4 hover:shadow-md transition-shadow border border-gray-200">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-file-pdf text-red-600 text-xl"></i>
                            </div>
                            <span class="px-2 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded">
                                {{ str_replace('_', ' ', $document->type_document) }}
                            </span>
                        </div>
                        
                        <h5 class="font-semibold text-gray-900 mb-1 text-sm line-clamp-2">{{ $document->titre }}</h5>
                        <p class="text-xs text-gray-500 mb-3">{{ $document->date_document->format('d/m/Y') }}</p>
                        
                        <a href="{{ route('patient.documents.download', $document) }}" 
                           class="w-full bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded text-xs font-medium flex items-center justify-center transition-colors">
                            <i class="fas fa-download mr-2"></i>
                            Télécharger
                        </a>
                    </div>
                @endforeach
            </div>
            
            <div class="mt-6">
                {{ $documents->links() }}
            </div>
        @endif
    </div>

    <!-- Ordonnances archivées -->
    <div x-show="activeTab === 'tous' || activeTab === 'ordonnances'" x-cloak>
        <h4 class="text-lg font-semibold text-gray-900 mb-4 mt-6">
            <i class="fas fa-prescription text-purple-600 mr-2"></i>
            Ordonnances archivées (+ de 3 mois)
        </h4>
        
        @if($ordonnances->isEmpty())
            <div class="text-center py-12">
                <i class="fas fa-prescription text-gray-300 text-5xl mb-3"></i>
                <p class="text-gray-500">Aucune ordonnance archivée</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($ordonnances as $ordonnance)
                    <div class="bg-purple-50 rounded-lg p-4 hover:shadow-md transition-shadow border border-purple-200">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-pills text-purple-600 text-xl"></i>
                            </div>
                            <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded">
                                {{ $ordonnance->medicaments->count() }} méd.
                            </span>
                        </div>
                        
                        <h5 class="font-semibold text-gray-900 mb-1 text-sm">
                            Dr. {{ $ordonnance->consultation->praticien->user->nom_complet }}
                        </h5>
                        <p class="text-xs text-gray-500 mb-3">{{ $ordonnance->created_at->format('d/m/Y') }}</p>
                        
                        <a href="{{ route('patient.documents.ordonnance.download', $ordonnance) }}" 
                           class="w-full bg-purple-600 hover:bg-purple-700 text-white px-3 py-2 rounded text-xs font-medium flex items-center justify-center transition-colors">
                            <i class="fas fa-download mr-2"></i>
                            Télécharger PDF
                        </a>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Résultats d'examens -->
    <div x-show="activeTab === 'tous' || activeTab === 'examens'" x-cloak>
        <h4 class="text-lg font-semibold text-gray-900 mb-4 mt-6">
            <i class="fas fa-flask text-orange-600 mr-2"></i>
            Résultats d'examens
        </h4>
        
        @if($examens->isEmpty())
            <div class="text-center py-12">
                <i class="fas fa-flask text-gray-300 text-5xl mb-3"></i>
                <p class="text-gray-500">Aucun résultat d'examen disponible</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                @foreach($examens as $examen)
                    <div class="bg-orange-50 rounded-lg p-4 hover:shadow-md transition-shadow border border-orange-200">
                        <div class="flex items-start justify-between mb-3">
                            <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                <i class="fas fa-vial text-orange-600 text-xl"></i>
                            </div>
                            <span class="px-2 py-1 bg-{{ $examen->statut === 'termine' ? 'green' : 'yellow' }}-100 text-{{ $examen->statut === 'termine' ? 'green' : 'yellow' }}-800 text-xs font-semibold rounded">
                                {{ $examen->statut }}
                            </span>
                        </div>
                        
                        <h5 class="font-semibold text-gray-900 mb-1 text-sm line-clamp-2">{{ $examen->titre }}</h5>
                        <p class="text-xs text-gray-600 mb-1">{{ $examen->type_examen }}</p>
                        <p class="text-xs text-gray-500 mb-3">{{ $examen->date_examen->format('d/m/Y') }}</p>
                        
                        @if($examen->fichier_resultat)
                            <a href="{{ route('patient.documents.examen.download', $examen) }}" 
                               class="w-full bg-orange-600 hover:bg-orange-700 text-white px-3 py-2 rounded text-xs font-medium flex items-center justify-center transition-colors">
                                <i class="fas fa-download mr-2"></i>
                                Télécharger
                            </a>
                        @else
                            <button disabled class="w-full bg-gray-300 text-gray-500 px-3 py-2 rounded text-xs font-medium cursor-not-allowed">
                                Pas de fichier
                            </button>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>

<!-- Modal Certificat médical -->
<div id="certificatModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
        <h3 class="text-xl font-bold text-gray-900 mb-4">Générer un certificat médical</h3>
        
        <form method="GET" action="{{ route('patient.documents.certificat.generate') }}">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Motif (optionnel)</label>
                <textarea name="motif" rows="3" 
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                          placeholder="Indiquez le motif de la demande..."></textarea>
            </div>
            
            <div class="flex justify-end space-x-3">
                <button type="button" onclick="closeCertificatModal()" 
                        class="px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Annuler
                </button>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">
                    Générer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function openCertificatModal() {
        document.getElementById('certificatModal').classList.remove('hidden');
    }
    
    function closeCertificatModal() {
        document.getElementById('certificatModal').classList.add('hidden');
    }
</script>
@endpush
