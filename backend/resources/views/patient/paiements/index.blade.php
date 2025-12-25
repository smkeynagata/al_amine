@extends('layouts.app')

@section('title', 'Historique des paiements')
@section('page-title', 'Historique des paiements')

@section('sidebar')
    @include('patient.partials.sidebar')
@endsection

@section('content')
<!-- Statistiques -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Total paiements</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['total_paiements'] }}</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-receipt text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Montant total</p>
                <p class="text-2xl font-bold text-green-600">{{ number_format($stats['montant_total'], 0, ',', ' ') }} FCFA</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-money-bill-wave text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Ce mois</p>
                <p class="text-2xl font-bold text-gray-900">{{ $stats['paiements_mois'] }}</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-calendar-alt text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Montant du mois</p>
                <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['montant_mois'], 0, ',', ' ') }} FCFA</p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-chart-line text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Statistiques par méthode -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-4">
        <i class="fas fa-chart-pie text-blue-600 mr-2"></i>
        Répartition par méthode de paiement
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        @foreach($statsMethodes as $stat)
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <p class="text-sm font-semibold text-gray-900 mb-1">{{ $stat->methode_paiement }}</p>
                <p class="text-xs text-gray-500 mb-2">{{ $stat->count }} transaction(s)</p>
                <p class="text-lg font-bold text-blue-600">{{ number_format($stat->total, 0, ',', ' ') }} FCFA</p>
            </div>
        @endforeach
    </div>
</div>

<!-- Filtres -->
<div class="bg-white rounded-lg shadow-md p-6 mb-6">
    <form method="GET" action="{{ route('patient.paiements.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date début</label>
            <input type="date" name="date_debut" value="{{ request('date_debut') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Date fin</label>
            <input type="date" name="date_fin" value="{{ request('date_fin') }}" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Méthode</label>
            <select name="methode" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                <option value="">Toutes</option>
                <option value="ESPECES" {{ request('methode') === 'ESPECES' ? 'selected' : '' }}>Espèces</option>
                <option value="WAVE" {{ request('methode') === 'WAVE' ? 'selected' : '' }}>Wave</option>
                <option value="ORANGE_MONEY" {{ request('methode') === 'ORANGE_MONEY' ? 'selected' : '' }}>Orange Money</option>
                <option value="FREE_MONEY" {{ request('methode') === 'FREE_MONEY' ? 'selected' : '' }}>Free Money</option>
                <option value="CARTE" {{ request('methode') === 'CARTE' ? 'selected' : '' }}>Carte</option>
            </select>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Montant min</label>
            <input type="number" name="montant_min" value="{{ request('montant_min') }}" placeholder="0" 
                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
        </div>

        <div class="flex items-end">
            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                <i class="fas fa-filter mr-2"></i>
                Filtrer
            </button>
        </div>
    </form>
</div>

<!-- Liste des paiements -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b">
        <h3 class="text-lg font-semibold text-gray-900">
            <i class="fas fa-list text-blue-600 mr-2"></i>
            Transactions ({{ $paiements->total() }})
        </h3>
    </div>

    @if($paiements->isEmpty())
        <div class="p-12 text-center">
            <i class="fas fa-receipt text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucun paiement</h3>
            <p class="text-gray-500">Vous n'avez effectué aucun paiement pour le moment</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Référence</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Praticien</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Méthode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($paiements as $paiement)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="font-mono text-sm font-semibold text-gray-900">{{ $paiement->reference }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($paiement->date_paiement)
                                    {{ $paiement->date_paiement->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-gray-400">En attente</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                @if($paiement->facture && $paiement->facture->consultation)
                                    Dr. {{ $paiement->facture->consultation->praticien->user->nom_complet }}
                                @else
                                    N/A
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if($paiement->methode_paiement === 'ESPECES') bg-green-100 text-green-800
                                    @elseif($paiement->methode_paiement === 'WAVE') bg-blue-100 text-blue-800
                                    @elseif($paiement->methode_paiement === 'ORANGE_MONEY') bg-orange-100 text-orange-800
                                    @elseif($paiement->methode_paiement === 'FREE_MONEY') bg-red-100 text-red-800
                                    @else bg-purple-100 text-purple-800
                                    @endif">
                                    {{ str_replace('_', ' ', $paiement->methode_paiement) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-600">
                                {{ number_format($paiement->montant, 0, ',', ' ') }} FCFA
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full
                                    @if(in_array($paiement->statut, ['VALIDE', 'PAYE'])) bg-green-100 text-green-800
                                    @elseif(in_array($paiement->statut, ['EN_ATTENTE', 'EN_ATTENTE_PAIEMENT'])) bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ $paiement->statut === 'PAYE' ? 'PAYÉ' : ($paiement->statut === 'EN_ATTENTE_PAIEMENT' ? 'EN ATTENTE DE PAIEMENT' : $paiement->statut) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('patient.paiements.show', $paiement) }}" 
                                   class="text-blue-600 hover:text-blue-700 mr-3">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('patient.paiements.recu', $paiement) }}" 
                                   class="text-red-600 hover:text-red-700">
                                    <i class="fas fa-file-pdf"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t">
            {{ $paiements->links() }}
        </div>
    @endif
</div>
@endsection
