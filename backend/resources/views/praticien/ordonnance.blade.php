@extends('praticien.layouts.app')

@section('title', 'Ordonnance')

@section('content')
<div class="mb-6">
    <a href="{{ route('praticien.consultations') }}" class="text-blue-600 hover:text-blue-700 flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Retour aux consultations
    </a>
</div>

<div class="bg-white rounded-lg shadow-lg p-8 max-w-4xl mx-auto">
    <!-- En-tête ordonnance -->
    <div class="border-b-2 border-blue-600 pb-6 mb-6">
        <div class="flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-blue-900 mb-2">ORDONNANCE MÉDICALE</h1>
                <p class="text-sm text-gray-600">Date: {{ now()->format('d/m/Y') }}</p>
            </div>
            <div class="text-right">
                <div class="bg-blue-600 text-white px-4 py-2 rounded-lg">
                    <p class="text-2xl font-bold">🏥</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Informations praticien -->
    <div class="grid grid-cols-2 gap-6 mb-8">
        <div class="bg-blue-50 rounded-lg p-4">
            <h3 class="text-lg font-bold text-blue-900 mb-3">Praticien</h3>
            <p class="text-sm font-semibold">{{ $consultation->praticien->user->nom_complet }}</p>
            <!-- Numéro d'ordre supprimé -->
            <p class="text-sm text-gray-600">{{ $consultation->praticien->service->nom }}</p>
            <p class="text-sm text-gray-600">Tél: {{ $consultation->praticien->user->telephone }}</p>
        </div>

        <div class="bg-green-50 rounded-lg p-4">
            <h3 class="text-lg font-bold text-green-900 mb-3">Patient</h3>
            <p class="text-sm font-semibold">{{ $consultation->patient->user->nom_complet }}</p>
            @if($consultation->patient->user->date_naissance)
                <p class="text-sm text-gray-600">Né(e) le: {{ $consultation->patient->user->date_naissance->format('d/m/Y') }}</p>
                <p class="text-sm text-gray-600">{{ $consultation->patient->user->date_naissance->age }} ans - {{ $consultation->patient->user->sexe === 'M' ? 'Masculin' : 'Féminin' }}</p>
            @else
                <p class="text-sm text-gray-600">{{ $consultation->patient->user->sexe === 'M' ? 'Masculin' : 'Féminin' }}</p>
            @endif
            <p class="text-sm text-gray-600">Tél: {{ $consultation->patient->user->telephone }}</p>
        </div>
    </div>

    @if($consultation->ordonnances->isEmpty())
    <!-- Formulaire de création d'ordonnance -->
    <form method="POST" action="{{ route('praticien.ordonnance.store', $consultation) }}" x-data="ordonnanceForm()">
        @csrf

        <div class="mb-8">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Médicaments prescrits</h3>
                <button type="button" @click="ajouterMedicament()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">
                    + Ajouter un médicament
                </button>
            </div>

            <div class="space-y-4">
                <template x-for="(med, index) in medicaments" :key="index">
                    <div class="border border-gray-300 rounded-lg p-4 bg-gray-50">
                        <div class="flex justify-between items-start mb-3">
                            <h4 class="font-semibold text-gray-800" x-text="'Médicament ' + (index + 1)"></h4>
                            <button type="button" @click="supprimerMedicament(index)" class="text-red-600 hover:text-red-700">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </button>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Nom du médicament *</label>
                                <input type="text" :name="'medicaments[' + index + '][nom]'" x-model="med.nom" required
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                                       placeholder="Ex: Paracétamol">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Posologie *</label>
                                <input type="text" :name="'medicaments[' + index + '][posologie]'" x-model="med.posologie" required
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                                       placeholder="Ex: 1g, 3 fois par jour">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Durée du traitement *</label>
                                <input type="text" :name="'medicaments[' + index + '][duree]'" x-model="med.duree" required
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                                       placeholder="Ex: 7 jours">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Instructions</label>
                                <input type="text" :name="'medicaments[' + index + '][instructions]'" x-model="med.instructions"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                                       placeholder="Ex: Prendre après les repas">
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Instructions générales -->
        <div class="mb-8">
            <label class="block text-sm font-semibold text-gray-700 mb-2">Instructions générales</label>
            <textarea name="conseils" rows="4"
                      class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500"
                      placeholder="Recommandations, précautions particulières..."></textarea>
        </div>

        <div class="flex justify-between items-center pt-6 border-t">
            <button type="button" onclick="window.print()" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg">
                🖨️ Imprimer
            </button>
            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-8 py-3 rounded-lg font-semibold">
                ✓ Valider l'ordonnance
            </button>
        </div>
    </form>
    @else
    <!-- Ordonnance existante -->
    @foreach($consultation->ordonnances as $ordonnance)
    <div class="mb-8">
        <h3 class="text-xl font-bold text-gray-800 mb-4">Médicaments prescrits</h3>

        <div class="space-y-3">
            @foreach($ordonnance->medicaments as $index => $med)
            <div class="border-l-4 border-blue-500 bg-blue-50 rounded-lg p-4">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h4 class="font-bold text-lg text-gray-800">{{ $index + 1 }}. {{ $med['nom'] }}</h4>
                        <p class="text-sm text-gray-600 mt-1">
                            <span class="font-semibold">Posologie:</span> {{ $med['posologie'] ?? $med['dosage'] ?? 'Non spécifié' }}
                        </p>
                        <p class="text-sm text-gray-600">
                            <span class="font-semibold">Durée:</span> {{ $med['duree'] ?? 'Non spécifié' }}
                        </p>
                        @if(isset($med['instructions']))
                        <p class="text-sm text-gray-600 mt-2">
                            <span class="font-semibold">Instructions:</span> {{ $med['instructions'] }}
                        </p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        @if($ordonnance->conseils)
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mt-6">
            <p class="text-sm font-bold text-yellow-900 mb-2">📌 Instructions générales</p>
            <p class="text-sm text-gray-700">{{ $ordonnance->conseils }}</p>
        </div>
        @endif

        <div class="mt-8 pt-6 border-t flex justify-between">
            <div class="text-sm text-gray-600">
                <p>Ordonnance créée le {{ $ordonnance->date_ordonnance->format('d/m/Y à H:i') }}</p>
            </div>
            <button onclick="window.print()" class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-3 rounded-lg">
                🖨️ Imprimer
            </button>
        </div>
    </div>
    @endforeach
    @endif
</div>

@push('scripts')
<script>
function ordonnanceForm() {
    return {
        medicaments: [
            { nom: '', posologie: '', duree: '', instructions: '' }
        ],
        ajouterMedicament() {
            this.medicaments.push({ nom: '', posologie: '', duree: '', instructions: '' });
        },
        supprimerMedicament(index) {
            if (this.medicaments.length > 1) {
                this.medicaments.splice(index, 1);
            }
        }
    }
}
</script>
@endpush

@push('styles')
<style>
@media print {
    .no-print, aside, header, nav, button {
        display: none !important;
    }
    body {
        background: white;
    }
    .bg-blue-50, .bg-green-50 {
        background: #f0f9ff !important;
    }
}
</style>
@endpush
@endsection

