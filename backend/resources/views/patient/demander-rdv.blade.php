@extends('layouts.app')

@section('title', 'Demander un RDV')
@section('page-title', 'Demander un Rendez-vous')
@section('breadcrumb', 'Patient > Demander RDV')

@section('sidebar')
@include('patient.partials.sidebar')
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-lg shadow-lg" x-data="demandeRdvForm()">
        <!-- Progress Steps -->
        <div class="px-8 py-6 border-b">
            <div class="flex items-center justify-between text-sm">
                <div class="flex items-center" :class="step >= 1 ? 'text-blue-600' : 'text-gray-400'">
                    <div class="rounded-full h-10 w-10 flex items-center justify-center border-2" :class="step >= 1 ? 'border-blue-600 bg-blue-50' : 'border-gray-300'">1</div>
                    <span class="ml-2 font-semibold hidden md:block">Spécialité</span>
                </div>
                <div class="flex-1 h-1 mx-2" :class="step >= 2 ? 'bg-blue-600' : 'bg-gray-300'"></div>
                <div class="flex items-center" :class="step >= 2 ? 'text-blue-600' : 'text-gray-400'">
                    <div class="rounded-full h-10 w-10 flex items-center justify-center border-2" :class="step >= 2 ? 'border-blue-600 bg-blue-50' : 'border-gray-300'">2</div>
                    <span class="ml-2 font-semibold hidden md:block">Praticien</span>
                </div>
                <div class="flex-1 h-1 mx-2" :class="step >= 3 ? 'bg-blue-600' : 'bg-gray-300'"></div>
                <div class="flex items-center" :class="step >= 3 ? 'text-blue-600' : 'text-gray-400'">
                    <div class="rounded-full h-10 w-10 flex items-center justify-center border-2" :class="step >= 3 ? 'border-blue-600 bg-blue-50' : 'border-gray-300'">3</div>
                    <span class="ml-2 font-semibold hidden md:block">Date & Heure</span>
                </div>
                <div class="flex-1 h-1 mx-2" :class="step >= 4 ? 'bg-blue-600' : 'bg-gray-300'"></div>
                <div class="flex items-center" :class="step >= 4 ? 'text-blue-600' : 'text-gray-400'">
                    <div class="rounded-full h-10 w-10 flex items-center justify-center border-2" :class="step >= 4 ? 'border-blue-600 bg-blue-50' : 'border-gray-300'">4</div>
                    <span class="ml-2 font-semibold hidden md:block">Paiement</span>
                </div>
                <div class="flex-1 h-1 mx-2" :class="step >= 5 ? 'bg-blue-600' : 'bg-gray-300'"></div>
                <div class="flex items-center" :class="step >= 5 ? 'text-blue-600' : 'text-gray-400'">
                    <div class="rounded-full h-10 w-10 flex items-center justify-center border-2" :class="step >= 5 ? 'border-blue-600 bg-blue-50' : 'border-gray-300'">5</div>
                    <span class="ml-2 font-semibold hidden md:block">Confirmation</span>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('patient.demander-rdv.store') }}">
            @csrf

            <!-- Step 1: Choisir la spécialité -->
            <div x-show="step === 1" class="p-8">
                <h3 class="text-2xl font-bold mb-6">Choisissez une spécialité</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($specialites as $spec)
                    <div @click="selectSpecialite({{ $spec->id }})"
                         :class="specialite == {{ $spec->id }} ? 'border-blue-600 bg-blue-50' : 'border-gray-300'"
                         class="border-2 rounded-lg p-6 cursor-pointer hover:border-blue-400 transition">
                        <div class="text-4xl mb-3">{{ $spec->icone }}</div>
                        <h4 class="text-lg font-bold">{{ $spec->nom }}</h4>
                        <p class="text-sm text-gray-600 mt-1">{{ $spec->description }}</p>
                        <p class="text-blue-600 font-semibold mt-2">{{ $spec->tarif_format }}</p>
                    </div>
                    @endforeach
                </div>
                <input type="hidden" name="specialite_id" x-model="specialite">
            </div>

            <!-- Step 2: Choisir le praticien -->
            <div x-show="step === 2" class="p-8">
                <h3 class="text-2xl font-bold mb-6">Choisissez un praticien</h3>
                <div class="space-y-4">
                    <template x-if="praticiensList.length > 0">
                        <div>
                            <template x-for="prat in praticiensList" :key="prat.id">
                                <div @click="praticien = prat.id"
                                     :class="praticien == prat.id ? 'border-blue-600 bg-blue-50' : 'border-gray-300'"
                                     class="border-2 rounded-lg p-4 cursor-pointer hover:border-blue-400 transition mb-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <h4 class="text-lg font-bold" x-text="prat.nom"></h4>
                                            <p class="text-sm text-gray-600" x-text="prat.experience"></p>
                                            <template x-if="prat.biographie">
                                                <p class="text-sm text-gray-500 mt-2" x-text="prat.biographie"></p>
                                            </template>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-blue-600 font-bold" x-text="prat.tarif"></p>
                                            <p class="text-xs text-gray-500" x-text="prat.service"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                    <template x-if="praticiensList.length === 0">
                        <div class="text-center py-8">
                            <p class="text-gray-500">Aucun praticien disponible pour cette spécialité</p>
                        </div>
                    </template>
                </div>
                <input type="hidden" name="praticien_id" x-model="praticien">
                <div class="flex justify-between mt-6">
                    <button type="button" @click="step = 1" class="bg-gray-300 hover:bg-gray-400 px-6 py-2 rounded-lg">Retour</button>
                    <button type="button" @click="step = 3" x-show="praticien" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">Suivant</button>
                </div>
            </div>

            <!-- Step 3: Date et heure -->
            <div x-show="step === 3" class="p-8">
                <h3 class="text-2xl font-bold mb-6">Choisissez la date et l'heure</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Date souhaitée</label>
                        <input type="datetime-local" name="date_heure_souhaitee" required
                               min="{{ date('Y-m-d\TH:i') }}"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Motif de consultation</label>
                        <textarea name="motif" required rows="4"
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500"
                                  placeholder="Décrivez brièvement le motif de votre consultation..."></textarea>
                    </div>
                </div>
                <div class="flex justify-between mt-6">
                    <button type="button" @click="step = 2" class="bg-gray-300 hover:bg-gray-400 px-6 py-2 rounded-lg">Retour</button>
                    <button type="button" @click="step = 4" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg">Suivant</button>
                </div>
            </div>

            <!-- Step 4: Mode de paiement -->
            <div x-show="step === 4" class="p-8">
                <h3 class="text-2xl font-bold mb-6">💳 Choisissez votre mode de paiement</h3>
                
                <!-- Choix: Payer en ligne ou sur place -->
                <div class="mb-8">
                    <p class="text-gray-600 mb-4">Comment souhaitez-vous payer votre consultation ?</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div @click="modePaiement = 'EN_LIGNE'" 
                             :class="modePaiement === 'EN_LIGNE' ? 'border-blue-600 bg-blue-50' : 'border-gray-300'"
                             class="border-2 rounded-lg p-6 cursor-pointer hover:border-blue-400 transition">
                            <div class="text-3xl mb-2">💳</div>
                            <h4 class="text-lg font-bold mb-1">Payer en ligne</h4>
                            <p class="text-sm text-gray-600">Carte bancaire, Wave, Orange Money</p>
                            <div class="mt-3 text-xs text-green-600 font-semibold">✓ Sécurisé & Instantané</div>
                        </div>
                        <div @click="modePaiement = 'SUR_PLACE'" 
                             :class="modePaiement === 'SUR_PLACE' ? 'border-blue-600 bg-blue-50' : 'border-gray-300'"
                             class="border-2 rounded-lg p-6 cursor-pointer hover:border-blue-400 transition">
                            <div class="text-3xl mb-2">🏥</div>
                            <h4 class="text-lg font-bold mb-1">Payer sur place</h4>
                            <p class="text-sm text-gray-600">Réglez au centre médical lors de votre visite</p>
                            <div class="mt-3 text-xs text-blue-600 font-semibold">✓ Espèces ou carte acceptées</div>
                        </div>
                    </div>
                </div>

                <!-- Options de paiement en ligne -->
                <div x-show="modePaiement === 'EN_LIGNE'" class="mb-6">
                    <p class="text-gray-700 font-medium mb-4">Sélectionnez votre méthode de paiement :</p>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div @click="methodePaiement = 'CARTE_BANCAIRE'" 
                             :class="methodePaiement === 'CARTE_BANCAIRE' ? 'border-blue-600 bg-blue-50' : 'border-gray-300'"
                             class="border-2 rounded-lg p-4 cursor-pointer hover:border-blue-400 transition text-center">
                            <div class="text-2xl mb-2">💳</div>
                            <h5 class="font-semibold">Carte Bancaire</h5>
                            <p class="text-xs text-gray-500 mt-1">Visa, Mastercard</p>
                        </div>
                        <div @click="methodePaiement = 'WAVE'" 
                             :class="methodePaiement === 'WAVE' ? 'border-blue-600 bg-blue-50' : 'border-gray-300'"
                             class="border-2 rounded-lg p-4 cursor-pointer hover:border-blue-400 transition text-center">
                            <div class="text-2xl mb-2">📱</div>
                            <h5 class="font-semibold text-blue-600">Wave</h5>
                            <p class="text-xs text-gray-500 mt-1">Mobile Money</p>
                        </div>
                        <div @click="methodePaiement = 'ORANGE_MONEY'" 
                             :class="methodePaiement === 'ORANGE_MONEY' ? 'border-blue-600 bg-blue-50' : 'border-gray-300'"
                             class="border-2 rounded-lg p-4 cursor-pointer hover:border-blue-400 transition text-center">
                            <div class="text-2xl mb-2">📲</div>
                            <h5 class="font-semibold text-orange-600">Orange Money</h5>
                            <p class="text-xs text-gray-500 mt-1">Mobile Money</p>
                        </div>
                    </div>
                </div>

                <!-- Montant estimé -->
                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-6 mb-6">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-600">Montant de la consultation</p>
                            <p class="text-3xl font-bold text-blue-600">15 000 <span class="text-lg">FCFA</span></p>
                        </div>
                        <div class="text-4xl">💰</div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">* Tarif indicatif, peut varier selon le praticien</p>
                </div>

                <input type="hidden" name="mode_paiement" x-model="modePaiement">
                <input type="hidden" name="methode_paiement" x-model="methodePaiement">

                <div class="flex justify-between mt-6">
                    <button type="button" @click="step = 3" class="bg-gray-300 hover:bg-gray-400 px-6 py-2 rounded-lg">Retour</button>
                    <button type="button" @click="step = 5" 
                            :disabled="modePaiement === 'EN_LIGNE' && !methodePaiement"
                            :class="(modePaiement === 'EN_LIGNE' && !methodePaiement) ? 'bg-gray-400 cursor-not-allowed' : 'bg-blue-600 hover:bg-blue-700'"
                            class="text-white px-6 py-2 rounded-lg">
                        Suivant
                    </button>
                </div>
            </div>

            <!-- Step 5: Confirmation -->
            <div x-show="step === 5" class="p-8">
                <h3 class="text-2xl font-bold mb-6">✅ Confirmation de votre demande</h3>
                <div class="bg-gray-50 rounded-lg p-6 space-y-4">
                    <div class="flex justify-between border-b pb-3">
                        <span class="text-gray-600">Spécialité:</span>
                        <span class="font-semibold" x-text="getSpecialiteNom()"></span>
                    </div>
                    <div class="flex justify-between border-b pb-3">
                        <span class="text-gray-600">Praticien:</span>
                        <span class="font-semibold" x-text="getPraticienNom()"></span>
                    </div>
                    <div class="flex justify-between border-b pb-3">
                        <span class="text-gray-600">Mode de paiement:</span>
                        <span class="font-semibold" x-text="modePaiement === 'EN_LIGNE' ? 'Paiement en ligne' : 'Paiement sur place'"></span>
                    </div>
                    <div x-show="modePaiement === 'EN_LIGNE'" class="flex justify-between border-b pb-3">
                        <span class="text-gray-600">Méthode:</span>
                        <span class="font-semibold" x-text="methodePaiement.replace(/_/g, ' ')"></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Statut:</span>
                        <span x-show="modePaiement === 'EN_LIGNE'" class="px-3 py-1 bg-orange-100 text-orange-800 rounded-full text-sm">💳 En attente de paiement</span>
                        <span x-show="modePaiement === 'SUR_PLACE'" class="px-3 py-1 bg-yellow-100 text-yellow-800 rounded-full text-sm">⏳ En attente de validation</span>
                    </div>
                </div>
                
                <div x-show="modePaiement === 'EN_LIGNE'" class="bg-green-50 border border-green-200 rounded-lg p-4 mt-6">
                    <p class="text-sm text-green-800">
                        ✓ <strong>Vous allez être redirigé vers PayDunya pour effectuer le paiement en toute sécurité.</strong>
                    </p>
                    <p class="text-xs text-green-700 mt-2">
                        Une fois votre paiement validé, vous recevrez une notification par email et votre demande sera mise en attente de validation par la secrétaire.
                    </p>
                </div>
                
                <div x-show="modePaiement === 'SUR_PLACE'" class="bg-blue-50 border border-blue-200 rounded-lg p-4 mt-6">
                    <p class="text-sm text-blue-800">
                        ℹ️ Votre demande sera traitée par nos secrétaires. Vous pourrez payer lors de votre visite au centre.
                    </p>
                </div>
                
                <div class="flex justify-between mt-6">
                    <button type="button" @click="step = 4" class="bg-gray-300 hover:bg-gray-400 px-6 py-2 rounded-lg">Retour</button>
                    
                    <!-- Bouton différent selon le mode de paiement -->
                    <button 
                        type="submit" 
                        x-show="modePaiement === 'EN_LIGNE'"
                        class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white px-8 py-3 rounded-lg font-semibold shadow-lg flex items-center gap-2">
                        💳 Procéder au paiement
                    </button>
                    
                    <button 
                        type="submit" 
                        x-show="modePaiement === 'SUR_PLACE'"
                        class="bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white px-8 py-3 rounded-lg font-semibold shadow-lg">
                        ✓ Confirmer la demande
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function demandeRdvForm() {
    return {
        step: 1,
        specialite: '',
        praticien: '',
        praticiensList: [],
        modePaiement: 'SUR_PLACE',
        methodePaiement: '',
        specialites: {!! json_encode($specialites->map(function($spec) {
            return [
                'id' => $spec->id,
                'nom' => $spec->nom,
                'praticiens' => $spec->praticiens->map(function($prat) {
                    return [
                        'id' => $prat->id,
                        'nom' => 'Dr. ' . $prat->user->nom_complet,
                        'experience' => $prat->annees_experience . ' ans d\'expérience',
                        'biographie' => $prat->biographie,
                        'tarif' => number_format($prat->tarif_consultation ?? 0, 0, ',', ' ') . ' FCFA',
                        'service' => $prat->service->nom ?? 'Service',
                    ];
                })->toArray(),
            ];
        })) !!},
        
        selectSpecialite(id) {
            this.specialite = id;
            const spec = this.specialites.find(s => s.id == id);
            this.praticiensList = spec ? spec.praticiens : [];
            this.praticien = '';
            this.step = 2;
        },
        
        getSpecialiteNom() {
            const spec = this.specialites.find(s => s.id == this.specialite);
            return spec ? spec.nom : '';
        },
        
        getPraticienNom() {
            const spec = this.specialites.find(s => s.id == this.specialite);
            if (!spec) return '';
            const prat = spec.praticiens.find(p => p.id == this.praticien);
            return prat ? prat.nom : '';
        }
    }
}
</script>
@endsection

