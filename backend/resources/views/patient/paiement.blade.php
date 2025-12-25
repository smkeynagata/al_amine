@extends('layouts.app')

@section('title', 'Paiement de Facture')
@section('page-title', 'Paiement de la Facture N° ' . $facture->numero_facture)
@section('breadcrumb', 'Patient > Factures > Paiement')

@section('sidebar')
@include('patient.partials.sidebar')
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-4">
        <a href="{{ route('patient.facture.show', $facture) }}" class="text-blue-600 hover:text-blue-700 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Retour à la facture
        </a>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Résumé de la facture -->
        <div class="md:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-6 sticky top-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Résumé</h3>
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Facture N°:</span>
                        <span class="font-semibold">{{ $facture->numero_facture }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Montant total:</span>
                        <span class="font-semibold">{{ number_format($facture->montant_total, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span class="text-gray-600">Déjà payé:</span>
                        <span class="font-semibold text-green-600">{{ number_format($facture->montant_paye, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="border-t pt-3">
                        <div class="flex justify-between">
                            <span class="font-bold text-gray-800">Reste à payer:</span>
                            <span class="font-bold text-red-600 text-xl">{{ number_format($facture->montant_restant, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>
                </div>
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <p class="text-xs text-blue-800">
                        💡 Vous pouvez effectuer un paiement partiel ou complet.
                    </p>
                </div>
            </div>
        </div>

        <!-- Formulaire de paiement -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Effectuer un paiement</h3>

                <form method="POST" action="{{ route('patient.paiement.traiter', $facture) }}" x-data="{ methode: '', montant: {{ $facture->montant_restant }} }">
                    @csrf

                    <!-- Montant à payer -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Montant à payer (FCFA)</label>
                        <input type="number" name="montant" x-model="montant" required
                               min="1" max="{{ $facture->montant_restant }}" step="1"
                               class="w-full border-2 border-gray-300 rounded-lg px-4 py-3 text-lg font-bold focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Entrez le montant">
                        <p class="text-sm text-gray-500 mt-1">
                            Maximum: {{ number_format($facture->montant_restant, 0, ',', ' ') }} FCFA
                        </p>
                        @error('montant')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Méthode de paiement -->
                    <div class="mb-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Méthode de paiement</label>
                        <div class="grid grid-cols-2 gap-4">
                            <!-- Espèces -->
                            <div @click="methode = 'ESPECES'"
                                 :class="methode === 'ESPECES' ? 'border-blue-600 bg-blue-50' : 'border-gray-300'"
                                 class="border-2 rounded-lg p-4 cursor-pointer hover:border-blue-400 transition">
                                <div class="flex items-center">
                                    <input type="radio" name="methode_paiement" value="ESPECES" x-model="methode" required class="mr-3">
                                    <div>
                                        <p class="font-semibold">💵 Espèces</p>
                                        <p class="text-xs text-gray-500">Paiement en caisse</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Wave -->
                            <div @click="methode = 'WAVE'"
                                 :class="methode === 'WAVE' ? 'border-blue-600 bg-blue-50' : 'border-gray-300'"
                                 class="border-2 rounded-lg p-4 cursor-pointer hover:border-blue-400 transition">
                                <div class="flex items-center">
                                    <input type="radio" name="methode_paiement" value="WAVE" x-model="methode" class="mr-3">
                                    <div>
                                        <p class="font-semibold">📱 Wave</p>
                                        <p class="text-xs text-gray-500">Mobile Money</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Orange Money -->
                            <div @click="methode = 'ORANGE_MONEY'"
                                 :class="methode === 'ORANGE_MONEY' ? 'border-blue-600 bg-blue-50' : 'border-gray-300'"
                                 class="border-2 rounded-lg p-4 cursor-pointer hover:border-blue-400 transition">
                                <div class="flex items-center">
                                    <input type="radio" name="methode_paiement" value="ORANGE_MONEY" x-model="methode" class="mr-3">
                                    <div>
                                        <p class="font-semibold">🍊 Orange Money</p>
                                        <p class="text-xs text-gray-500">Mobile Money</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Free Money -->
                            <div @click="methode = 'FREE_MONEY'"
                                 :class="methode === 'FREE_MONEY' ? 'border-blue-600 bg-blue-50' : 'border-gray-300'"
                                 class="border-2 rounded-lg p-4 cursor-pointer hover:border-blue-400 transition">
                                <div class="flex items-center">
                                    <input type="radio" name="methode_paiement" value="FREE_MONEY" x-model="methode" class="mr-3">
                                    <div>
                                        <p class="font-semibold">📲 Free Money</p>
                                        <p class="text-xs text-gray-500">Mobile Money</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Carte bancaire -->
                            <div @click="methode = 'CARTE'"
                                 :class="methode === 'CARTE' ? 'border-blue-600 bg-blue-50' : 'border-gray-300'"
                                 class="border-2 rounded-lg p-4 cursor-pointer hover:border-blue-400 transition col-span-2">
                                <div class="flex items-center">
                                    <input type="radio" name="methode_paiement" value="CARTE" x-model="methode" class="mr-3">
                                    <div>
                                        <p class="font-semibold">💳 Carte Bancaire</p>
                                        <p class="text-xs text-gray-500">Visa, Mastercard</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @error('methode_paiement')
                        <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Instructions Mobile Money -->
                    <div x-show="['WAVE', 'ORANGE_MONEY', 'FREE_MONEY'].includes(methode)" class="mb-6">
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-sm text-yellow-800 font-semibold mb-2">📌 Instructions:</p>
                            <ol class="text-sm text-yellow-800 space-y-1 list-decimal list-inside">
                                <li>Composez le code de votre service mobile money</li>
                                <li>Entrez le numéro: <strong>77 123 45 67</strong></li>
                                <li>Confirmez le montant: <strong><span x-text="montant.toLocaleString()"></span> FCFA</strong></li>
                                <li>Validez la transaction</li>
                                <li>Cliquez sur "Confirmer le paiement" ci-dessous</li>
                            </ol>
                        </div>
                    </div>

                    <!-- Récapitulatif -->
                    <div x-show="methode && montant > 0" class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h4 class="font-semibold text-gray-800 mb-3">Récapitulatif de votre paiement</h4>
                        <div class="space-y-2 text-sm">
                            <div class="flex justify-between">
                                <span>Montant à payer:</span>
                                <span class="font-semibold" x-text="montant.toLocaleString() + ' FCFA'"></span>
                            </div>
                            <div class="flex justify-between">
                                <span>Méthode:</span>
                                <span class="font-semibold" x-text="methode.replace('_', ' ')"></span>
                            </div>
                            <div class="flex justify-between pt-2 border-t">
                                <span>Reste après paiement:</span>
                                <span class="font-semibold text-green-600" x-text="({{ $facture->montant_restant }} - montant).toLocaleString() + ' FCFA'"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons -->
                    <div class="flex justify-between">
                        <a href="{{ route('patient.facture.show', $facture) }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-6 py-3 rounded-lg">
                            Annuler
                        </a>
                        <button type="submit" x-show="methode && montant > 0"
                                class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold">
                            ✓ Confirmer le paiement
                        </button>
                    </div>
                </form>
            </div>

            <!-- Informations de sécurité -->
            <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <h4 class="font-semibold text-blue-800 mb-2">🔒 Paiement sécurisé</h4>
                <p class="text-sm text-blue-700">
                    Toutes vos transactions sont sécurisées et cryptées. Vos informations de paiement ne sont jamais stockées sur nos serveurs.
                </p>
            </div>
        </div>
    </div>
</div>

@endsection

