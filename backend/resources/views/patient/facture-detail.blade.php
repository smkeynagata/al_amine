@extends('layouts.app')

@section('title', 'Détails de la Facture')
@section('page-title', 'Facture N° ' . $facture->numero_facture)
@section('breadcrumb', 'Patient > Factures > Détails')

@section('sidebar')
@include('patient.partials.sidebar')
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-4">
        <a href="{{ route('patient.factures') }}" class="text-blue-600 hover:text-blue-700 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Retour aux factures
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-8">
        <!-- En-tête de la facture -->
        <div class="flex justify-between items-start mb-8 pb-6 border-b-2">
            <div>
                <h1 class="text-3xl font-bold text-gray-800 mb-2">FACTURE</h1>
                <p class="text-gray-600">N° {{ $facture->numero_facture }}</p>
                <p class="text-sm text-gray-500">Date: {{ $facture->date_facture->format('d/m/Y') }}</p>
            </div>
            <div class="text-right">
                <div class="px-4 py-2 rounded-lg {{ $facture->statut === 'PAYEE' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    <p class="font-bold text-lg">
                        {{ $facture->statut === 'PAYEE' ? '✓ PAYÉE' : '⏳ IMPAYÉE' }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Informations patient et consultation -->
        @php
            $demande = $facture->demandeRdv;
            $consultation = $facture->consultation;
            $evenement = $consultation?->rendezVous ?? $demande;
            $evenementDate = $consultation?->rendezVous?->date_heure ?? $demande?->date_heure_souhaitee;
            $praticienNom = $consultation?->rendezVous?->praticien?->user?->nom_complet
                ?? $demande?->praticien?->user?->nom_complet;
        @endphp

        <div class="grid grid-cols-2 gap-8 mb-8">
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Informations Patient</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm mb-1"><span class="font-semibold">Nom:</span> {{ $facture->patient->user->nom_complet }}</p>
                    <p class="text-sm mb-1"><span class="font-semibold">Tel:</span> {{ $facture->patient->user->telephone }}</p>
                    <p class="text-sm"><span class="font-semibold">Email:</span> {{ $facture->patient->user->email }}</p>
                </div>
            </div>
            <div>
                <h3 class="text-lg font-semibold text-gray-700 mb-3">Rendez-vous</h3>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm mb-1"><span class="font-semibold">Type:</span> {{ $consultation ? 'Consultation' : 'Demande de rendez-vous' }}</p>
                    <p class="text-sm mb-1"><span class="font-semibold">Référence:</span>
                        @if($consultation)
                            #{{ $consultation->id }}
                        @elseif($demande)
                            #{{ $demande->id }}
                        @else
                            —
                        @endif
                    </p>
                    <p class="text-sm mb-1"><span class="font-semibold">Professionnel:</span> {{ $praticienNom ? 'Dr. ' . $praticienNom : 'Non communiqué' }}</p>
                    <p class="text-sm"><span class="font-semibold">Date:</span> {{ $evenementDate ? $evenementDate->format('d/m/Y à H:i') : $facture->date_facture->format('d/m/Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Détails de la facturation -->
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-700 mb-3">Détails de la Facturation</h3>
            <table class="w-full border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border p-3 text-left">Désignation</th>
                        <th class="border p-3 text-right">Montant</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $lignes = $facture->lignes ?? [];
                        $items = is_array($lignes)
                            ? ($lignes['items'] ?? (array_is_list($lignes) ? $lignes : []))
                            : [];
                    @endphp
                    @if(!empty($items))
                        @foreach($items as $ligne)
                            @if(is_array($ligne))
                            <tr>
                                <td class="border p-3">{{ $ligne['designation'] ?? 'Service médical' }}</td>
                                <td class="border p-3 text-right">{{ number_format($ligne['montant'] ?? 0, 0, ',', ' ') }} FCFA</td>
                            </tr>
                            @endif
                        @endforeach
                    @else
                        <tr>
                            <td class="border p-3">Consultation médicale</td>
                            <td class="border p-3 text-right">{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50 font-bold">
                        <td class="border p-3">TOTAL</td>
                        <td class="border p-3 text-right text-lg">{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    <tr class="bg-green-50">
                        <td class="border p-3 text-green-700">Montant payé</td>
                        <td class="border p-3 text-right text-green-700">{{ number_format($facture->montant_paye, 0, ',', ' ') }} FCFA</td>
                    </tr>
                    <tr class="bg-red-50 font-bold">
                        <td class="border p-3 text-red-700">Montant restant</td>
                        <td class="border p-3 text-right text-red-700 text-lg">{{ number_format($facture->montant_restant, 0, ',', ' ') }} FCFA</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Historique des paiements -->
        @if($facture->paiements->isNotEmpty())
        <div class="mb-8">
            <h3 class="text-lg font-semibold text-gray-700 mb-3">Historique des Paiements</h3>
            <div class="space-y-2">
                @foreach($facture->paiements as $paiement)
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex justify-between items-center">
                    <div>
                        <p class="font-semibold text-green-800">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</p>
                        <p class="text-sm text-gray-600">{{ $paiement->date_paiement->format('d/m/Y à H:i') }}</p>
                        <p class="text-sm text-gray-600">Méthode: {{ $paiement->methode_paiement }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-gray-500">Réf: {{ $paiement->reference }}</p>
                        <span class="px-2 py-1 bg-green-600 text-white rounded text-xs">{{ $paiement->statut }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="flex justify-between items-center pt-6 border-t">
            <button onclick="window.print()" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Imprimer
            </button>
            @if($facture->statut !== 'PAYEE' && $facture->montant_restant > 0)
            <a href="{{ route('patient.paiement', $facture) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                </svg>
                Procéder au paiement
            </a>
            @endif
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        .no-print {
            display: none !important;
        }
        body {
            background: white;
        }
    }
</style>
@endpush
@endsection

