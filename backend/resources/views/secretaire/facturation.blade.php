@extends('secretaire.layouts.app')

@section('title', 'Facturation')

@section('content')
@php
    $aFacturerCount = $consultationsSansFacture->count();
    $facturesTotal = $factures->total();
    $montantFactures = $factures->sum('montant');
@endphp

<div class="mb-8 bg-gradient-to-r from-purple-600 via-indigo-600 to-blue-600 rounded-2xl p-6 text-white shadow-xl">
    <h2 class="text-2xl font-bold mb-2 flex items-center gap-3">
        <span class="text-3xl">🧾</span>
        Gestion de la facturation
    </h2>
    <p class="text-indigo-100">Créez et suivez les factures des consultations médicales</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-lg border border-purple-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <span class="text-3xl font-bold text-purple-600">{{ $aFacturerCount }}</span>
        </div>
        <p class="text-sm font-semibold text-gray-600">Consultations à facturer</p>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-lg border border-blue-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <span class="text-3xl font-bold text-blue-600">{{ $facturesTotal }}</span>
        </div>
        <p class="text-sm font-semibold text-gray-600">Factures générées</p>
    </div>

    <div class="bg-white rounded-2xl p-6 shadow-lg border border-green-100">
        <div class="flex items-center justify-between mb-3">
            <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <span class="text-3xl font-bold text-green-600">{{ number_format($montantFactures, 0, ',', ' ') }}</span>
        </div>
        <p class="text-sm font-semibold text-gray-600">Montant sur la page (FCFA)</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Consultations à facturer</h3>
                <p class="text-sm text-gray-500">Validez les consultations pour générer les factures</p>
            </div>
        </div>
        <div class="p-6 space-y-4">
            @if($aFacturerCount === 0)
                <div class="text-center py-10">
                    <p class="text-gray-500 text-sm">Aucune consultation en attente de facturation.</p>
                </div>
            @else
                @foreach($consultationsSansFacture as $consultation)
                    <div class="border border-gray-100 rounded-xl p-4 hover:border-purple-200 hover:shadow-md transition">
                        <div class="flex flex-col gap-3">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-xs uppercase text-gray-500">Patient</p>
                                    <h4 class="text-sm font-semibold text-gray-900">{{ $consultation->patient->user->nom_complet }}</h4>
                                </div>
                                <span class="text-xs font-semibold text-purple-600 bg-purple-50 px-3 py-1 rounded-full">{{ optional($consultation->date_consultation)->format('d/m/Y') }}</span>
                            </div>
                            <div class="text-sm text-gray-600">
                                <p>Praticien : Dr. {{ $consultation->praticien->user->nom_complet }}</p>
                                <p>Motif : {{ $consultation->motif }}</p>
                            </div>
                            <div class="flex justify-end">
                                <a href="{{ route('secretaire.facture.generer', $consultation) }}" class="px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 text-white text-sm font-semibold rounded-lg hover:shadow-lg transition">
                                    Générer la facture
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-lg border border-gray-100">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="text-xl font-bold text-gray-900">Historique des factures</h3>
                <p class="text-sm text-gray-500">Suivi des factures générées et de leur statut</p>
            </div>
        </div>
        <div class="p-6 overflow-x-auto">
            @if($factures->isEmpty())
                <div class="text-center py-10">
                    <p class="text-gray-500 text-sm">Aucune facture enregistrée pour le moment.</p>
                </div>
            @else
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-gray-500 uppercase text-xs">
                            <th class="pb-3">Facture</th>
                            <th class="pb-3">Patient</th>
                            <th class="pb-3">Praticien</th>
                            <th class="pb-3">Montant</th>
                            <th class="pb-3">Statut</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($factures as $facture)
                            <tr class="hover:bg-gray-50">
                                <td class="py-3 font-semibold text-gray-900">{{ $facture->numero_facture }}</td>
                                <td class="py-3 text-gray-700">{{ $facture->patient?->user?->nom_complet ?? 'Patient inconnu' }}</td>
                                <td class="py-3 text-gray-700">Dr. {{ $facture->consultation?->praticien?->user?->nom_complet ?? '—' }}</td>
                                <td class="py-3 font-semibold text-green-600">{{ number_format($facture->montant_total ?? 0, 0, ',', ' ') }} FCFA</td>
                                <td class="py-3">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                        {{ $facture->statut === 'PAYEE' ? 'bg-green-100 text-green-800' :
                                           ($facture->statut === 'ANNULEE' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ $facture->statut }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="mt-6">
                    {{ $factures->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
