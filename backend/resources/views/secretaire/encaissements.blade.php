@extends('secretaire.layouts.app')

@section('title', 'Encaissements')

@section('content')
<div class="mb-8 bg-gradient-to-r from-green-600 via-emerald-600 to-teal-600 rounded-2xl p-6 text-white shadow-xl">
    <h2 class="text-2xl font-bold mb-2 flex items-center gap-2">
        <span class="text-3xl">💰</span>
        Gestion des Encaissements
    </h2>
    <p class="text-green-100">Suivi des paiements et encaissements</p>
</div>

<!-- Statistiques -->
<div class="grid grid-cols-4 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-lg border border-green-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <span class="text-3xl font-bold text-green-600">{{ number_format($stats['total_jour'], 0, ',', ' ') }}</span>
        </div>
        <p class="text-sm font-semibold text-gray-600">Encaissements Aujourd'hui (FCFA)</p>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-lg border border-blue-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <span class="text-3xl font-bold text-blue-600">{{ number_format($stats['total_mois'], 0, ',', ' ') }}</span>
        </div>
        <p class="text-sm font-semibold text-gray-600">Total Ce Mois (FCFA)</p>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-lg border border-purple-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <span class="text-3xl font-bold text-purple-600">{{ $stats['nb_paiements_jour'] }}</span>
        </div>
        <p class="text-sm font-semibold text-gray-600">Paiements Aujourd'hui</p>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-lg border border-red-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <span class="text-3xl font-bold text-red-600">{{ $stats['factures_impayees'] }}</span>
        </div>
        <p class="text-sm font-semibold text-gray-600">Factures Impayées</p>
    </div>
</div>

<!-- Liste des Paiements -->
<div class="bg-white rounded-2xl p-6 shadow-lg">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-gray-900">Historique des Paiements</h3>
        <div class="flex gap-2">
            <select class="bg-gray-100 rounded-lg px-4 py-2 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-purple-500">
                <option>Tous les paiements</option>
                <option>Aujourd'hui</option>
                <option>Cette semaine</option>
                <option>Ce mois</option>
            </select>
        </div>
    </div>

    @if($paiements->isNotEmpty())
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-left py-4 px-4 text-sm font-semibold text-gray-600">Date</th>
                    <th class="text-left py-4 px-4 text-sm font-semibold text-gray-600">Patient</th>
                    <th class="text-left py-4 px-4 text-sm font-semibold text-gray-600">N° Facture</th>
                    <th class="text-left py-4 px-4 text-sm font-semibold text-gray-600">Montant</th>
                    <th class="text-left py-4 px-4 text-sm font-semibold text-gray-600">Mode Paiement</th>
                    <th class="text-left py-4 px-4 text-sm font-semibold text-gray-600">Statut</th>
                    <th class="text-right py-4 px-4 text-sm font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($paiements as $paiement)
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                    <td class="py-4 px-4 text-sm text-gray-700">
                        {{ $paiement->created_at->format('d/m/Y H:i') }}
                    </td>
                    <td class="py-4 px-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-gradient-to-br from-purple-400 to-pink-400 text-white rounded-lg flex items-center justify-center font-bold text-xs">
                                {{ substr($paiement->facture?->patient?->user?->prenom ?? 'P', 0, 1) }}{{ substr($paiement->facture?->patient?->user?->nom ?? 'N', 0, 1) }}
                            </div>
                            <span class="font-semibold text-gray-900">{{ $paiement->facture?->patient?->user?->nom_complet ?? 'Patient inconnu' }}</span>
                        </div>
                    </td>
                    <td class="py-4 px-4 text-sm font-medium text-gray-700">
                        {{ $paiement->facture?->numero_facture ?? '—' }}
                    </td>
                    <td class="py-4 px-4">
                        <span class="text-sm font-bold text-green-600">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</span>
                    </td>
                    <td class="py-4 px-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                            {{ $paiement->mode_paiement === 'ESPECES' ? 'bg-green-100 text-green-800' : 
                               ($paiement->mode_paiement === 'WAVE' ? 'bg-blue-100 text-blue-800' :
                               ($paiement->mode_paiement === 'ORANGE_MONEY' ? 'bg-orange-100 text-orange-800' :
                               ($paiement->mode_paiement === 'CARTE' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800'))) }}">
                            {{ str_replace('_', ' ', $paiement->mode_paiement) }}
                        </span>
                    </td>
                    <td class="py-4 px-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                            {{ $paiement->statut === 'VALIDE' ? 'bg-green-100 text-green-800' : 
                               ($paiement->statut === 'EN_ATTENTE' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                            {{ $paiement->statut }}
                        </span>
                    </td>
                    <td class="py-4 px-4 text-right">
                        <button class="px-3 py-1 bg-purple-600 text-white rounded-lg text-xs font-semibold hover:bg-purple-700 transition">
                            Reçu
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $paiements->links() }}
    </div>
    @else
    <div class="text-center py-12">
        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
        </svg>
        <p class="text-gray-500">Aucun paiement enregistré</p>
    </div>
    @endif
</div>
@endsection
