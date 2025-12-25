@extends('layouts.app')

@section('title', 'Mes Factures')
@section('page-title', 'Mes Factures')
@section('breadcrumb', 'Patient > Factures')

@section('sidebar')
@include('patient.partials.sidebar')
@endsection

@section('content')
<div class="mb-6">
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-semibold text-gray-700">Gestion de vos factures</h3>
            <p class="text-sm text-gray-500">Consultez et payez vos factures de consultation</p>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Total Factures</p>
                <p class="text-2xl font-bold text-gray-800">{{ $factures->count() }}</p>
            </div>
            <div class="bg-blue-100 rounded-full p-3">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">En attente</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $factures->where('statut', 'EMISE')->count() }}</p>
            </div>
            <div class="bg-yellow-100 rounded-full p-3">
                <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Payées</p>
                <p class="text-2xl font-bold text-green-600">{{ $factures->where('statut', 'PAYEE')->count() }}</p>
            </div>
            <div class="bg-green-100 rounded-full p-3">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500">Montant Total</p>
                <p class="text-2xl font-bold text-purple-600">{{ number_format($factures->sum('montant_total'), 0, ',', ' ') }} FCFA</p>
            </div>
            <div class="bg-purple-100 rounded-full p-3">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white rounded-lg shadow-sm p-4 mb-6">
    <div class="flex flex-wrap gap-4">
        <div class="flex-1 min-w-[200px]">
            <label class="block text-sm font-medium text-gray-700 mb-1">Filtrer par statut</label>
            <select class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500" onchange="window.location.href=this.value">
                <option value="{{ route('patient.factures') }}">Tous les statuts</option>
                <option value="{{ route('patient.factures', ['statut' => 'EMISE']) }}" {{ request('statut') == 'EMISE' ? 'selected' : '' }}>En attente</option>
                <option value="{{ route('patient.factures', ['statut' => 'PAYEE']) }}" {{ request('statut') == 'PAYEE' ? 'selected' : '' }}>Payée</option>
                <option value="{{ route('patient.factures', ['statut' => 'ANNULEE']) }}" {{ request('statut') == 'ANNULEE' ? 'selected' : '' }}>Annulée</option>
            </select>
        </div>
    </div>
</div>

@if($factures->isEmpty())
    <!-- Empty State -->
    <div class="bg-white rounded-lg shadow-sm p-12 text-center">
        <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucune facture</h3>
        <p class="text-gray-500">Vous n'avez pas encore de facture</p>
    </div>
@else
    <!-- Factures List -->
    <div class="space-y-4">
        @foreach($factures as $facture)
        <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow">
            <div class="p-6">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <div class="flex items-center mb-2">
                            <h4 class="text-lg font-bold text-gray-800">Facture N° {{ $facture->numero_facture }}</h4>
                            <span class="ml-3 px-3 py-1 rounded-full text-sm font-semibold
                                @if($facture->statut === 'EMISE') bg-yellow-100 text-yellow-800
                                @elseif($facture->statut === 'PAYEE') bg-green-100 text-green-800
                                @elseif($facture->statut === 'ANNULEE') bg-red-100 text-red-800
                                @endif">
                                @if($facture->statut === 'EMISE') ⏳ En attente
                                @elseif($facture->statut === 'PAYEE') ✓ Payée
                                @elseif($facture->statut === 'ANNULEE') ✗ Annulée
                                @endif
                            </span>
                        </div>
                        @php
                            $praticienNom = $facture->consultation?->rendezVous?->praticien?->user?->nom_complet
                                ?? $facture->demandeRdv?->praticien?->user?->nom_complet;
                            $dateReference = $facture->consultation?->rendezVous?->date_heure
                                ?? $facture->demandeRdv?->date_heure_souhaitee;
                        @endphp
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold">Professionnel:</span>
                            {{ $praticienNom ? 'Dr. ' . $praticienNom : 'Non renseigné' }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold">Date prévue:</span>
                            {{ $dateReference ? $dateReference->format('d/m/Y à H:i') : '—' }}
                        </p>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">Émise le</p>
                        <p class="text-sm font-semibold">{{ $facture->date_facture ? $facture->date_facture->format('d/m/Y') : $facture->created_at->format('d/m/Y') }}</p>
                    </div>
                </div>

                <!-- Détails de la facturation -->
                <div class="bg-gray-50 rounded-lg p-4 mb-4">
                    <div class="space-y-2">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Montant consultation:</span>
                            <span class="font-semibold">{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</span>
                        </div>
                        @if($facture->consultation && $facture->montant_paye > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Actes médicaux:</span>
                            <span class="font-semibold">{{ number_format($facture->montant_paye, 0, ',', ' ') }} FCFA</span>
                        </div>
                        @endif
                        @if($facture->consultation && $facture->montant_restant > 0)
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Médicaments:</span>
                            <span class="font-semibold">{{ number_format($facture->montant_restant, 0, ',', ' ') }} FCFA</span>
                        </div>
                        @endif
                        <div class="border-t pt-2 mt-2">
                            <div class="flex justify-between text-base">
                                <span class="font-bold text-gray-800">Montant Total:</span>
                                <span class="font-bold text-blue-600 text-lg">{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</span>
                            </div>
                        </div>
                    </div>
                </div>

                @if(isset($facture->lignes['notes']) ? $facture->lignes['notes'] : null)
                <div class="bg-blue-50 rounded-lg p-3 mb-4">
                    <p class="text-sm text-gray-700">
                        <span class="font-semibold">Notes:</span> {{ isset($facture->lignes['notes']) ? $facture->lignes['notes'] : null }}
                    </p>
                </div>
                @endif

                @if($facture->statut === 'PAYEE')
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-semibold text-green-800 mb-1">✓ Facture payée</p>
                            @if($facture->paiements->isNotEmpty())
                            <p class="text-sm text-green-700">
                                <span class="font-semibold">Dernier paiement:</span> {{ $facture->paiements->last()->date_paiement ? $facture->paiements->last()->date_paiement->format('d/m/Y à H:i') : 'N/A' }}
                            </p>
                            <p class="text-sm text-green-700">
                                <span class="font-semibold">Méthode:</span> {{ $facture->paiements->last()->methode_paiement }}
                            </p>
                            @endif
                        </div>
                        <a href="{{ route('patient.facture.show', $facture) }}" target="_blank" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg text-sm flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Télécharger
                        </a>
                    </div>
                </div>
                @endif

                @if($facture->statut === 'EMISE')
                <div class="border-t pt-4 mt-4">
                    <div class="flex items-center justify-between">
                        <div class="text-sm text-gray-600">
                            <p class="font-semibold mb-1">💳 Paiement requis</p>
                            <p>Veuillez procéder au paiement de cette facture</p>
                        </div>
                        <div class="flex space-x-2">
                            <a href="{{ route('patient.facture.show', $facture) }}" target="_blank" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg text-sm">
                                Voir détails
                            </a>
                            <a href="{{ route('patient.paiement', $facture) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-semibold">
                                Payer maintenant
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $factures->links() }}
    </div>
@endif

@endsection

@push('scripts')
<script>
    // Confirmation before payment
    document.querySelectorAll('a[href*="paiement"]').forEach(link => {
        link.addEventListener('click', function(e) {
            if (!this.href.includes('show')) {
                e.preventDefault();
                Swal.fire({
                    title: 'Confirmer le paiement',
                    text: 'Vous allez être redirigé vers la page de paiement',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonText: 'Continuer',
                    cancelButtonText: 'Annuler',
                    confirmButtonColor: '#2563eb'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = this.href;
                    }
                });
            }
        });
    });
</script>
@endpush
