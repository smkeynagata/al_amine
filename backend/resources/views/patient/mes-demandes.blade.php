@extends('layouts.app')

@section('title', 'Mes Demandes de RDV')
@section('page-title', 'Mes Demandes de Rendez-vous')
@section('breadcrumb', 'Patient > Mes Demandes')

@section('sidebar')
@include('patient.partials.sidebar')
@endsection

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h3 class="text-lg font-semibold text-gray-700">Historique de vos demandes</h3>
        <p class="text-sm text-gray-500">Suivez l'état de vos demandes de rendez-vous</p>
    </div>
    <a href="{{ route('patient.demander-rdv') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
        </svg>
        Nouvelle demande
    </a>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm p-4 mb-6">
    <div class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-medium text-gray-700 mb-1">Filtrer par statut</label>
            <select class="w-full rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" onchange="if (this.value) window.location.href=this.value">
                <option value="{{ route('patient.mes-demandes') }}">Tous les statuts</option>
                <option value="{{ route('patient.mes-demandes', ['statut' => 'EN_ATTENTE']) }}" {{ request('statut') == 'EN_ATTENTE' ? 'selected' : '' }}>⏳ En attente de validation</option>
                <option value="{{ route('patient.mes-demandes', ['statut' => 'EN_ATTENTE_PAIEMENT']) }}" {{ request('statut') == 'EN_ATTENTE_PAIEMENT' ? 'selected' : '' }}>🔔 RDV à confirmer</option>
                <option value="{{ route('patient.mes-demandes', ['statut' => 'CONFIRMEE']) }}" {{ request('statut') == 'CONFIRMEE' ? 'selected' : '' }}>✅ Confirmée</option>
                <option value="{{ route('patient.mes-demandes', ['statut' => 'REFUSEE']) }}" {{ request('statut') == 'REFUSEE' ? 'selected' : '' }}>❌ Refusée</option>
                <option value="{{ route('patient.mes-demandes', ['statut' => 'ANNULEE']) }}" {{ request('statut') == 'ANNULEE' ? 'selected' : '' }}>🚫 Annulée</option>
            </select>
        </div>
    </div>
</div>

@if($demandes->isEmpty())
    <!-- Empty State -->
    <div class="bg-white rounded-lg shadow-sm p-12 text-center">
        <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
        </svg>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucune demande trouvée</h3>
        <p class="text-gray-500 mb-6">Vous n'avez pas encore fait de demande de rendez-vous</p>
        <a href="{{ route('patient.demander-rdv') }}" class="inline-flex items-center bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Faire une demande
        </a>
    </div>
@else
    <!-- Demandes List -->
    <div class="space-y-4">
        @foreach($demandes as $demande)
        <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <h4 class="text-lg font-bold text-gray-800">Dr. {{ $demande->praticien->user->nom_complet }}</h4>
                            <span class="ml-3 px-3 py-1 rounded-full text-sm font-semibold
                                @if($demande->statut === 'EN_ATTENTE_PAIEMENT') bg-blue-100 text-blue-800
                                @elseif($demande->statut === 'PAYEE') bg-blue-100 text-blue-800
                                @elseif($demande->statut === 'CONFIRMEE') bg-green-100 text-green-800
                                @elseif($demande->statut === 'REFUSEE') bg-red-100 text-red-800
                                @elseif($demande->statut === 'ANNULEE') bg-gray-100 text-gray-800
                                @endif">
                                @if($demande->statut === 'EN_ATTENTE_PAIEMENT') 🔔 RDV à confirmer
                                @elseif($demande->statut === 'PAYEE') ✅ Payé - En attente de validation
                                @elseif($demande->statut === 'CONFIRMEE') ✅ Confirmée
                                @elseif($demande->statut === 'REFUSEE') ❌ Refusée
                                @elseif($demande->statut === 'ANNULEE') 🚫 Annulée
                                @endif
                            </span>
                        </div>
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold">Spécialité:</span> {{ $demande->specialite->nom }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold">Service:</span> {{ $demande->praticien->service->nom }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Demandé le</p>
                        <p class="text-sm font-semibold">{{ $demande->created_at->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm font-semibold text-gray-700">Date souhaitée</span>
                        </div>
                        <p class="text-sm text-gray-900 ml-7">{{ $demande->date_heure_souhaitee->format('d/m/Y à H:i') }}</p>
                    </div>

                    @if($demande->date_traitement)
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="flex items-center mb-2">
                            <svg class="w-5 h-5 text-purple-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span class="text-sm font-semibold text-gray-700">Traitée le</span>
                        </div>
                        <p class="text-sm text-gray-900 ml-7">{{ $demande->date_traitement->format('d/m/Y à H:i') }}</p>
                        @if($demande->traitePar)
                        <p class="text-xs text-gray-500 ml-7 mt-1">Par {{ $demande->traitePar->user->nom_complet }}</p>
                        @endif
                    </div>
                    @endif
                </div>

                <div class="bg-blue-50 rounded-lg p-4 mb-4">
                    <p class="text-sm font-semibold text-gray-700 mb-1">Motif de consultation</p>
                    <p class="text-sm text-gray-600">{{ $demande->motif }}</p>
                </div>

                @if($demande->reponse_secretaire)
                <div class="border-t pt-4">
                    <div class="bg-{{ $demande->statut === 'VALIDEE' ? 'green' : 'red' }}-50 rounded-lg p-4">
                        <p class="text-sm font-semibold text-gray-700 mb-1">
                            @if($demande->statut === 'VALIDEE')
                                ✓ Réponse de la secrétaire
                            @else
                                ✗ Motif du refus
                            @endif
                        </p>
                        <p class="text-sm text-gray-600">{{ $demande->reponse_secretaire }}</p>
                    </div>
                </div>
                @endif

                @if($demande->statut === 'VALIDEE' && $demande->rendezVous)
                <div class="border-t pt-4 mt-4">
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-semibold text-green-800 mb-1">✓ Rendez-vous confirmé</p>
                                <p class="text-sm text-green-700">
                                    <span class="font-semibold">Date:</span> {{ $demande->rendezVous->date_heure->format('d/m/Y à H:i') }}
                                </p>
                                <p class="text-sm text-green-700">
                                    <span class="font-semibold">Durée:</span> {{ $demande->rendezVous->duree_minutes }} minutes
                                </p>
                            </div>
                            <a href="{{ route('patient.mes-rdv') }}" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm">
                                Voir les détails
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                @if($demande->statut === 'EN_ATTENTE')
                <div class="border-t pt-4 mt-4">
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                        <p class="text-sm text-yellow-800">
                            ⏳ Votre demande est en cours de traitement par nos secrétaires. Vous serez notifié dès qu'elle sera traitée.
                        </p>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $demandes->links() }}
    </div>
@endif

@endsection

@push('scripts')
<script>
    // Auto-refresh page every 30 seconds if there are pending requests
    @if($demandes->where('statut', 'EN_ATTENTE')->isNotEmpty())
    setTimeout(function() {
        location.reload();
    }, 30000);
    @endif
</script>
@endpush

