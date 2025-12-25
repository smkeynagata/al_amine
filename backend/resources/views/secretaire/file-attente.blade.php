@extends('secretaire.layouts.app')

@section('title', "File d'attente")

@section('content')
<style>[x-cloak]{display:none!important}</style>

@php
    $selectedStatut = $statut ?? 'EN_ATTENTE';
    $statusClasses = [
        'EN_ATTENTE' => 'from-yellow-50 to-orange-50 border-yellow-500',
        'CONFIRMEE' => 'from-green-50 to-emerald-50 border-green-500',
        'REFUSEE' => 'from-red-50 to-pink-50 border-red-500',
    ];
    $pillClasses = [
        'EN_ATTENTE' => 'bg-yellow-100 text-yellow-800',
        'CONFIRMEE' => 'bg-green-100 text-green-800',
        'REFUSEE' => 'bg-red-100 text-red-800',
    ];
@endphp

<div class="mb-8 bg-gradient-to-r from-blue-600 via-blue-700 to-blue-900 rounded-2xl p-8 text-white shadow-xl">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-3xl font-bold mb-2 flex items-center gap-3">
                <div class="bg-white/20 p-3 rounded-xl">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </div>
                File d'attente
            </h2>
            <p class="text-blue-100 text-lg">Gérez et traitez les demandes de rendez-vous en attente</p>
        </div>
        <div class="text-right">
            <p class="text-blue-200 text-sm">Total demandes</p>
            <p class="text-4xl font-bold">{{ $stats['EN_ATTENTE'] ?? 0 }}</p>
        </div>
    </div>
</div>

<!-- Statistiques -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-xl p-6 shadow-md border border-yellow-200 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-yellow-600 uppercase tracking-wide">En attente</p>
                <p class="text-4xl font-bold text-yellow-900 mt-2">{{ $stats['EN_ATTENTE'] ?? 0 }}</p>
                <p class="text-xs text-yellow-600 mt-1">À traiter</p>
            </div>
            <div class="w-16 h-16 bg-yellow-200 rounded-xl flex items-center justify-center">
                <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 shadow-md border border-green-200 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-green-600 uppercase tracking-wide">Confirmées</p>
                <p class="text-4xl font-bold text-green-900 mt-2">{{ $stats['CONFIRMEE'] ?? 0 }}</p>
                <p class="text-xs text-green-600 mt-1">Validées</p>
            </div>
            <div class="w-16 h-16 bg-green-200 rounded-xl flex items-center justify-center">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-6 shadow-md border border-red-200 hover:shadow-lg transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-semibold text-red-600 uppercase tracking-wide">Refusées</p>
                <p class="text-4xl font-bold text-red-900 mt-2">{{ $stats['REFUSEE'] ?? 0 }}</p>
                <p class="text-xs text-red-600 mt-1">Rejetées</p>
            </div>
            <div class="w-16 h-16 bg-red-200 rounded-xl flex items-center justify-center">
                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Liste des Demandes -->
<div class="bg-white rounded-2xl shadow-lg">
    <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-gray-900">Demandes de rendez-vous</h3>
            <p class="text-sm text-gray-500">Filtrez et traitez les demandes entrantes</p>
        </div>
        <form method="GET" class="flex items-center gap-2">
            <select name="statut" class="bg-gray-100 rounded-lg px-4 py-2 text-sm font-medium focus:outline-none focus:ring-2 focus:ring-indigo-500" onchange="this.form.submit()">
                <option value="EN_ATTENTE" {{ $selectedStatut === 'EN_ATTENTE' ? 'selected' : '' }}>En attente</option>
                <option value="CONFIRMEE" {{ $selectedStatut === 'CONFIRMEE' ? 'selected' : '' }}>Confirmées</option>
                <option value="REFUSEE" {{ $selectedStatut === 'REFUSEE' ? 'selected' : '' }}>Refusées</option>
                <option value="TOUS" {{ $selectedStatut === 'TOUS' ? 'selected' : '' }}>Toutes les demandes</option>
            </select>
        </form>
    </div>

    <div class="p-6">
        @if($demandes->isNotEmpty())
            <div class="space-y-4">
                @foreach($demandes as $demande)
                @php
                    $bagName = 'demande_' . $demande->id;
                    $formErrors = $errors->getBag($bagName);
                    $isFocusedForm = old('form_demande_id') == $demande->id;
                    $defaultPracticienId = $isFocusedForm ? old('praticien_id') : ($demande->praticien_id ?? optional($demande->praticien)->id);
                    $defaultSlot = $isFocusedForm ? old('date_heure_rdv') : optional($demande->date_heure_souhaitee)->format('Y-m-d\TH:i');
                    $defaultDuree = (int) ($isFocusedForm ? old('duree', 30) : 30);
                    $eligiblePracticiens = $praticiensData
                        ->filter(function ($info) use ($demande) {
                            return !$demande->specialite_id || collect($info['specialites'])->contains($demande->specialite_id);
                        })
                        ->values();

                    if ($defaultPracticienId && $praticiensData->has((int) $defaultPracticienId) && $eligiblePracticiens->where('id', (int) $defaultPracticienId)->isEmpty()) {
                        $eligiblePracticiens->push($praticiensData->get((int) $defaultPracticienId));
                    }

                    $eligiblePracticiens = $eligiblePracticiens->values();

                    $shouldOpenConfirm = $formErrors->any() || $isFocusedForm;

                    $manualDateDefault = $defaultSlot ? substr($defaultSlot, 0, 10) : '';
                    $manualTimeDefault = $defaultSlot ? substr($defaultSlot, 11, 5) : '';

                    $reprogBaseUrl = route('secretaire.demande.reprogrammation', $demande);

                    $initialPlannerState = [
                        'openConfirm' => $shouldOpenConfirm,
                        'openRefuse' => false,
                        'praticiens' => $eligiblePracticiens,
                        'selectedPracticien' => $defaultPracticienId ? (string) $defaultPracticienId : null,
                        'creneaux' => [],
                        'selectedSlot' => $defaultSlot ?: null,
                        'duree' => $defaultDuree,
                        'manualDate' => $manualDateDefault,
                        'manualTime' => $manualTimeDefault,
                        'manualStatus' => null,
                        'manualMessage' => '',
                        'manualStatusClasses' => '',
                        'reprogUrl' => $reprogBaseUrl,
                        'workHours' => [
                            'start' => \App\Services\PlanningService::DEFAULT_START_HOUR,
                            'end' => \App\Services\PlanningService::DEFAULT_END_HOUR,
                        ],
                    ];
                @endphp
                <div x-data='praticienPlanner(@json($initialPlannerState))'
                    x-init="init()"
                    class="bg-white border border-gray-200 rounded-xl p-6 hover:shadow-xl transition-all duration-300 hover:border-blue-300">
                    <div class="flex flex-col gap-4">
                        <div class="flex flex-col lg:flex-row lg:items-start lg:justify-between gap-4">
                            <div class="flex items-start gap-4 flex-1">
                                <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-pink-400 text-white rounded-xl flex items-center justify-center font-bold shadow-lg flex-shrink-0">
                                    {{ substr($demande->patient->user->prenom, 0, 1) }}{{ substr($demande->patient->user->name, 0, 1) }}
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-900 text-lg mb-1">{{ $demande->patient->user->nom_complet }}</h4>
                                    <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600 mb-2">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                            </svg>
                                            Dr. {{ $demande->praticien->user->nom_complet }}
                                        </span>
                                        <span class="flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                            </svg>
                                            {{ $demande->specialite->nom }}
                                        </span>
                                    </div>
                                    <div class="flex flex-wrap items-center gap-3 text-sm">
                                        <span class="flex items-center gap-1 text-gray-700 font-medium">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                            {{ optional($demande->date_heure_souhaitee)->format('d/m/Y à H:i') }}
                                        </span>
                                        <span class="inline-flex items-center px-4 py-2 rounded-full text-xs font-bold
                                            @if($demande->statut === 'EN_ATTENTE') bg-yellow-100 text-yellow-800 border border-yellow-300
                                            @elseif($demande->statut === 'PAYEE') bg-blue-100 text-blue-800 border border-blue-300
                                            @elseif($demande->statut === 'CONFIRMEE') bg-green-100 text-green-800 border border-green-300
                                            @elseif($demande->statut === 'REFUSEE') bg-red-100 text-red-800 border border-red-300
                                            @else bg-gray-100 text-gray-800 border border-gray-300
                                            @endif">
                                            @if($demande->statut === 'EN_ATTENTE') ⏳ En attente
                                            @elseif($demande->statut === 'PAYEE') ✅ Payé
                                            @elseif($demande->statut === 'CONFIRMEE') ✓ Confirmée
                                            @elseif($demande->statut === 'REFUSEE') ✕ Refusée
                                            @else {{ $demande->statut }}
                                            @endif
                                        </span>
                                    </div>
                                    @if($demande->motif)
                                    <p class="text-sm text-gray-600 mt-2 italic">{{ $demande->motif }}</p>
                                    @endif
                                </div>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                @if($demande->statut === 'EN_ATTENTE' || $demande->statut === 'PAYEE')
                                <button type="button" @click="toggleConfirm()" class="px-5 py-2 bg-gradient-to-r from-green-500 to-emerald-500 hover:from-green-600 hover:to-emerald-600 text-white rounded-lg text-sm font-semibold shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Programmer
                                </button>
                                <button type="button" @click="toggleRefuse()" class="px-5 py-2 bg-gradient-to-r from-red-500 to-pink-500 hover:from-red-600 hover:to-pink-600 text-white rounded-lg text-sm font-semibold shadow-md hover:shadow-lg transition-all flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                    Refuser
                                </button>
                                @endif
                            </div>
                        </div>

                        @if($demande->statut === 'EN_ATTENTE' || $demande->statut === 'PAYEE')
                        <div x-show="openConfirm" x-cloak class="bg-white border border-green-100 rounded-xl p-5 shadow-inner">
                            <h5 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                                <span class="w-8 h-8 bg-green-100 text-green-600 rounded-full flex items-center justify-center">📅</span>
                                Planifier le rendez-vous
                            </h5>
                            <form method="POST" action="{{ route('secretaire.demande.valider', $demande) }}" class="space-y-4">
                                @csrf
                                <input type="hidden" name="form_demande_id" value="{{ $demande->id }}">
                                <input type="hidden" name="date_heure_rdv" :value="selectedSlot">

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Praticien</label>
                                        <select name="praticien_id" x-model="selectedPracticien" @change="loadCreneaux(selectedPracticien)" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500" :disabled="!praticiens.length">
                                            <option value="">Sélectionner un praticien</option>
                                            <template x-for="praticien in praticiens" :key="praticien.id">
                                                <option :value="praticien.id" x-text="praticien.nom"></option>
                                            </template>
                                        </select>
                                        @error('praticien_id', $bagName)
                                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                        <p x-show="!praticiens.length" class="mt-2 text-xs text-yellow-600">Aucun praticien associé à cette spécialité n'est disponible sur les deux prochaines semaines.</p>
                                    </div>

                                    <div class="md:col-span-2">
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Créneau disponible</label>
                                        <template x-if="creneaux.length">
                                            <select x-model="selectedSlot" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                                                <template x-for="creneau in creneaux" :key="creneau.value">
                                                    <option :value="creneau.value" x-text="creneau.label"></option>
                                                </template>
                                            </select>
                                        </template>
                                        <template x-if="!creneaux.length">
                                            <div class="space-y-3">
                                                <div class="rounded-lg border border-yellow-200 bg-yellow-50 px-3 py-2 text-xs text-yellow-700">
                                                    Aucun créneau libre n'a été trouvé sur cette période. Choisissez manuellement une date dans les disponibilités du praticien.
                                                </div>
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                                    <div>
                                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Date souhaitée</label>
                                                        <input type="date" x-model="manualDate" min="{{ now()->format('Y-m-d') }}" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                                                    </div>
                                                    <div>
                                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Heure</label>
                                                        <input type="time" x-model="manualTime" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                                                    </div>
                                                </div>
                                                <div x-show="manualMessage" x-cloak class="rounded-lg px-3 py-2 text-xs" :class="manualStatusClasses">
                                                    <span x-text="manualMessage"></span>
                                                </div>
                                                <div class="pt-2">
                                                    <a :href="selectedPracticien ? `${reprogUrl}?praticien_id=${selectedPracticien}` : reprogUrl"
                                                       class="inline-flex items-center gap-2 rounded-lg border border-purple-200 bg-white px-4 py-2 text-xs font-semibold text-purple-600 transition hover:bg-purple-50">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                        Voir l'agenda détaillé
                                                    </a>
                                                </div>
                                            </div>
                                        </template>
                                        @error('date_heure_rdv', $bagName)
                                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-gray-600 mb-1">Durée (minutes)</label>
                                        <select name="duree" x-model="duree" class="w-full rounded-lg border-gray-300 focus:border-green-500 focus:ring-green-500">
                                            @foreach([15, 30, 45, 60, 75, 90, 120] as $duree)
                                                <option value="{{ $duree }}">{{ $duree }} minutes</option>
                                            @endforeach
                                        </select>
                                        @error('duree', $bagName)
                                            <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <button type="submit" class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg text-sm font-semibold shadow-md disabled:cursor-not-allowed disabled:opacity-60" :disabled="!selectedSlot || (!creneaux.length && manualStatus !== 'ok')">
                                    Confirmer le rendez-vous
                                </button>
                            </form>
                        </div>

                        <div x-show="openRefuse" x-cloak class="bg-white border border-red-100 rounded-xl p-5 shadow-inner">
                            <h5 class="text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                                <span class="w-8 h-8 bg-red-100 text-red-600 rounded-full flex items-center justify-center">📝</span>
                                Motif du refus
                            </h5>
                            <form method="POST" action="{{ route('secretaire.demande.refuser', $demande) }}" class="space-y-4">
                                @csrf
                                <div>
                                    <textarea name="reponse_secretaire" rows="3" class="w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500" placeholder="Expliquez la raison du refus" required></textarea>
                                </div>
                                <button type="submit" class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-sm font-semibold shadow-md">Confirmer le refus</button>
                            </form>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $demandes->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="text-gray-500">Aucune demande de rendez-vous trouvée pour ce filtre.</p>
            </div>
        @endif
    </div>
</div>
@endsection

    @push('scripts')
    <script>
        function praticienPlanner(initialState) {
            return {
                ...initialState,
                workHours: initialState.workHours || { start: 8, end: 18 },
                init() {
                    if (this.selectedPracticien) {
                        this.loadCreneaux(this.selectedPracticien, true);
                    } else if (this.praticiens.length) {
                        this.selectedPracticien = String(this.praticiens[0].id);
                        this.loadCreneaux(this.selectedPracticien);
                    } else {
                        this.selectedSlot = null;
                    }

                    this.$watch('manualDate', () => {
                        if (!this.creneaux.length) {
                            this.updateManualSlot();
                        }
                    });

                    this.$watch('manualTime', () => {
                        if (!this.creneaux.length) {
                            this.updateManualSlot();
                        }
                    });

                    this.$watch('duree', () => {
                        if (!this.creneaux.length) {
                            this.updateManualSlot();
                        }
                    });
                },
                toggleConfirm() {
                    this.openConfirm = !this.openConfirm;
                    this.openRefuse = false;

                    if (this.openConfirm) {
                        if (!this.selectedPracticien && this.praticiens.length) {
                            this.selectedPracticien = String(this.praticiens[0].id);
                        }
                        if (this.selectedPracticien) {
                            this.loadCreneaux(this.selectedPracticien, true);
                        }
                    }
                },
                toggleRefuse() {
                    this.openRefuse = !this.openRefuse;
                    this.openConfirm = false;
                },
                loadCreneaux(id, preserve = false) {
                    const found = this.praticiens.find(praticien => String(praticien.id) === String(id));
                    this.creneaux = found ? found.creneaux : [];

                    if (this.creneaux.length) {
                        if (!preserve || !this.selectedSlot || !this.creneaux.find(creneau => creneau.value === this.selectedSlot)) {
                            this.selectedSlot = this.creneaux[0].value;
                        }
                        this.manualStatus = null;
                        this.manualMessage = '';
                        this.manualStatusClasses = '';
                    } else {
                        if (!preserve) {
                            this.selectedSlot = null;
                        }

                        this.hydrateManualFromSlot();
                        this.updateManualSlot();
                    }
                },
                getSelectedPracticien() {
                    return this.praticiens.find(praticien => String(praticien.id) === String(this.selectedPracticien)) || null;
                },
                hydrateManualFromSlot() {
                    if (!this.selectedSlot) {
                        this.manualDate = '';
                        this.manualTime = '';
                        return;
                    }

                    const [datePart, timePart] = this.selectedSlot.split('T');
                    this.manualDate = datePart || '';
                    this.manualTime = (timePart || '').slice(0, 5);
                },
                updateManualSlot() {
                    const date = this.manualDate;
                    const time = this.manualTime;

                    if (!date || !time) {
                        this.selectedSlot = null;
                        this.manualStatus = null;
                        this.manualMessage = '';
                        this.manualStatusClasses = '';
                        return;
                    }

                    const slot = `${date}T${time}`;
                    this.selectedSlot = slot;

                    const status = this.evaluateManualSlot(slot);

                    const pad = (value) => String(value).padStart(2, '0');
                    const windowMessage = `Ce créneau sort de la plage standard ${pad(this.workHours.start ?? 8)}:00 - ${pad(this.workHours.end ?? 18)}:00.`;

                    const feedback = {
                        ok: {
                            message: 'Créneau disponible pour ce praticien.',
                            classes: 'bg-green-50 border border-green-200 text-green-700',
                        },
                        conflict: {
                            message: 'Créneau déjà occupé par un autre rendez-vous.',
                            classes: 'bg-red-50 border border-red-200 text-red-700',
                        },
                        window: {
                            message: windowMessage,
                            classes: 'bg-yellow-50 border border-yellow-200 text-yellow-700',
                        },
                        past: {
                            message: 'Le rendez-vous doit être fixé dans le futur.',
                            classes: 'bg-orange-50 border border-orange-200 text-orange-700',
                        },
                        invalid: {
                            message: 'Sélectionnez un praticien, une date et une heure valides.',
                            classes: 'bg-gray-50 border border-gray-200 text-gray-600',
                        },
                    }[status] ?? { message: '', classes: '' };

                    this.manualStatus = status;
                    this.manualMessage = feedback.message;
                    this.manualStatusClasses = feedback.classes;
                },
                evaluateManualSlot(slot) {
                    const praticien = this.getSelectedPracticien();

                    if (!praticien) {
                        return 'invalid';
                    }

                    const slotStart = new Date(slot);

                    if (Number.isNaN(slotStart.getTime())) {
                        return 'invalid';
                    }

                    const now = new Date();
                    if (slotStart <= now) {
                        return 'past';
                    }

                    const dureeMinutes = Number(this.duree || 0);
                    if (!dureeMinutes || Number.isNaN(dureeMinutes)) {
                        return 'invalid';
                    }
                    const slotEnd = new Date(slotStart.getTime() + dureeMinutes * 60000);

                    const slotStartMinutes = slotStart.getHours() * 60 + slotStart.getMinutes();
                    const slotEndMinutes = slotEnd.getHours() * 60 + slotEnd.getMinutes();

                    const workStartMinutes = (this.workHours?.start ?? 8) * 60;
                    const workEndMinutes = (this.workHours?.end ?? 18) * 60;

                    if (slotStartMinutes < workStartMinutes || slotEndMinutes > workEndMinutes) {
                        return 'window';
                    }

                    const hasConflict = (praticien.rdvs || []).some(rdv => {
                        const rdvStart = new Date(rdv.start);
                        const rdvEnd = new Date(rdv.end);

                        return slotStart < rdvEnd && slotEnd > rdvStart;
                    });

                    if (hasConflict) {
                        return 'conflict';
                    }

                    return 'ok';
                },
            };
        }
    </script>
    @endpush
