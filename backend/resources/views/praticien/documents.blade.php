@extends('praticien.layouts.app')

@section('title', 'Mes Documents')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-elegant">
        <div class="text-4xl font-bold text-gray-900 mb-1">{{ $stats['total_ordonnances'] }}</div>
        <div class="text-sm text-gray-500">Total ordonnances</div>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-elegant">
        <div class="text-4xl font-bold text-gray-900 mb-1">{{ $stats['ordonnances_mois'] }}</div>
        <div class="text-sm text-gray-500">Ordonnances ce mois</div>
    </div>
</div>

<!-- Documents List -->
<div class="bg-white rounded-2xl p-6 shadow-elegant">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-900">Ordonnances récentes</h2>
        <button class="bg-gradient-to-r from-purple-600 to-blue-600 text-white px-6 py-2 rounded-lg font-semibold text-sm hover:opacity-90 transition">
            + Nouvelle ordonnance
        </button>
    </div>

    @if($ordonnances->isNotEmpty())
    <div class="grid grid-cols-1 gap-4">
        @foreach($ordonnances as $consultation)
            @foreach($consultation->ordonnances as $ordonnance)
            <div class="border border-gray-200 rounded-xl p-5 hover:shadow-md transition">
                <div class="flex items-start justify-between">
                    <div class="flex items-start space-x-4 flex-1">
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-purple-500 rounded-xl flex items-center justify-center text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-2">
                                <h3 class="font-semibold text-gray-900">Ordonnance #{{ $ordonnance->id }}</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-800">
                                    Délivrée
                                </span>
                            </div>
                            <div class="flex items-center space-x-4 text-sm text-gray-600 mb-3">
                                <span>{{ $consultation->patient->user->nom_complet }}</span>
                                <span>{{ $consultation->date_consultation->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2 ml-4">
                        <a href="{{ route('praticien.ordonnance', $consultation) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-200 transition">
                            Voir
                        </a>
                        <button class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg text-sm font-medium hover:opacity-90 transition">
                            Télécharger
                        </button>
                    </div>
                </div>
            </div>
            @endforeach
        @endforeach
    </div>
    @else
    <div class="text-center py-12">
        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <p class="text-gray-500">Aucun document disponible</p>
    </div>
    @endif
</div>
@endsection

