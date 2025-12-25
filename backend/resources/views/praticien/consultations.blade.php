@extends('praticien.layouts.app')

@section('title', 'Mes Consultations')

@section('content')
<div class="mb-6">
    <h3 class="text-lg font-semibold text-gray-700">Toutes vos consultations</h3>
    <p class="text-sm text-gray-500">Consultez l'historique de vos consultations</p>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm p-4 mb-6">
    <div class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-medium text-gray-700 mb-1">Rechercher un patient</label>
            <input type="text" placeholder="Nom du patient..." class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
        </div>
        <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-medium text-gray-700 mb-1">Période</label>
            <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                <option>Toutes les périodes</option>
                <option>Aujourd'hui</option>
                <option>Cette semaine</option>
                <option>Ce mois</option>
            </select>
        </div>
    </div>
</div>

<!-- Consultations List -->
@if(isset($consultations) && $consultations->isNotEmpty())
<div class="space-y-4">
    @foreach($consultations as $consultation)
    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition">
        <div class="p-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex-1">
                    <div class="flex items-center mb-2">
                        <h4 class="text-lg font-bold text-gray-800">{{ $consultation->rendezVous->patient->user->nom_complet }}</h4>
                        <span class="ml-3 px-3 py-1 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                            ✓ Terminée
                        </span>
                    </div>
                    <p class="text-sm text-gray-600">
                        <span class="font-semibold">Date:</span> {{ $consultation->rendezVous->date_heure_rdv->format('d/m/Y à H:i') }}
                    </p>
                    <p class="text-sm text-gray-600">
                        <span class="font-semibold">Téléphone:</span> {{ $consultation->rendezVous->patient->user->telephone }}
                    </p>
                </div>
            </div>

            <!-- Consultation Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="bg-blue-50 rounded-lg p-4">
                    <p class="text-sm font-semibold text-gray-700 mb-1">Motif de consultation</p>
                    <p class="text-sm text-gray-600">{{ $consultation->motif ?? $consultation->rendezVous->motif ?? 'Non spécifié' }}</p>
                </div>
                <div class="bg-purple-50 rounded-lg p-4">
                    <p class="text-sm font-semibold text-gray-700 mb-1">Diagnostic</p>
                    <p class="text-sm text-gray-600">{{ Str::limit($consultation->diagnostic ?? 'N/A', 100) }}</p>
                </div>
            </div>

            @if($consultation->traitement)
            <div class="bg-green-50 rounded-lg p-4 mb-4">
                <p class="text-sm font-semibold text-gray-700 mb-1">Traitement prescrit</p>
                <p class="text-sm text-gray-600">{{ Str::limit($consultation->traitement, 150) }}</p>
            </div>
            @endif

            <div class="flex justify-end space-x-2">
                <a href="{{ route('praticien.ordonnance', $consultation) }}" class="bg-purple-100 hover:bg-purple-200 text-purple-700 px-4 py-2 rounded-lg text-sm font-semibold">
                    📋 Ordonnance
                </a>
            </div>
        </div>
    </div>
    @endforeach

    <!-- Pagination -->
    <div class="mt-6">
        {{ $consultations->links() }}
    </div>
</div>
@else
<div class="bg-white rounded-lg shadow-sm p-12 text-center">
    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
    </svg>
    <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucune consultation</h3>
    <p class="text-gray-500">Vous n'avez pas encore effectué de consultation</p>
</div>
@endif
@endsection

