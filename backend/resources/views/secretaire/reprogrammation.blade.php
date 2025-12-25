@extends('secretaire.layouts.app')

@section('title', 'Reprogrammer un rendez-vous')

@section('content')
<style>[x-cloak]{display:none!important}</style>

<div class="flex flex-col gap-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Reprogrammer la demande</h1>
            <p class="text-sm text-gray-500 mt-1">Sélectionnez un praticien et un créneau proposé sur l'agenda visuel.</p>
        </div>
        <a href="{{ route('secretaire.file-attente') }}" class="inline-flex items-center gap-2 rounded-lg border border-gray-200 px-4 py-2 text-sm font-semibold text-gray-600 hover:bg-gray-100">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Retour à la file d'attente
        </a>
    </div>

    <div class="grid gap-6 xl:grid-cols-3">
        <div class="space-y-6 xl:col-span-1">
            <div class="rounded-2xl border border-purple-100 bg-white p-5 shadow-sm">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 flex items-center justify-center rounded-xl bg-gradient-to-br from-purple-500 to-indigo-500 text-white font-bold">
                        {{ substr($demande->patient->user->prenom, 0, 1) }}{{ substr($demande->patient->user->name, 0, 1) }}
                    </div>
                    <div>
                        <h2 class="text-lg font-semibold text-gray-900">{{ $demande->patient->user->nom_complet }}</h2>
                        <p class="text-sm text-gray-500">Demande du {{ optional($demande->created_at)->format('d/m/Y à H:i') }}</p>
                    </div>
                </div>

                <dl class="mt-5 space-y-3 text-sm text-gray-600">
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-700">Spécialité</dt>
                        <dd>{{ $demande->specialite->nom ?? 'Non spécifiée' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-700">Praticien suggéré</dt>
                        <dd>{{ optional($demande->praticien?->user)->nom_complet ?? 'À sélectionner' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="font-medium text-gray-700">Créneau souhaité</dt>
                        <dd>{{ optional($demande->date_heure_souhaitee)->format('d/m/Y à H:i') ?? 'Non précisé' }}</dd>
                    </div>
                    <div>
                        <dt class="font-medium text-gray-700">Motif</dt>
                        <dd class="mt-1 text-gray-600">{{ $demande->motif ?? '—' }}</dd>
                    </div>
                </dl>
            </div>

            @if($selectedPraticien)
            <div class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900">Fenêtre de visualisation</h3>
                <p class="mt-1 text-xs text-gray-500">Agenda affiché du {{ $periodeDebut->locale('fr')->isoFormat('dddd D MMMM') }} au {{ $periodeFin->locale('fr')->isoFormat('dddd D MMMM') }}.</p>
                <p class="mt-3 text-sm text-gray-600">Les créneaux proposés couvrent la plage standard {{ sprintf('%02d:00', $workHours['start']) }} - {{ sprintf('%02d:00', $workHours['end']) }}. Seules les collisions avec des rendez-vous existants sont bloquées.</p>
            </div>
            @endif

            <div class="rounded-2xl border border-blue-100 bg-white p-5 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900">Changer de praticien</h3>
                <p class="mt-1 text-xs text-gray-500">Choisissez un autre praticien compatible si nécessaire.</p>
                <form method="GET" action="{{ route('secretaire.demande.reprogrammation', $demande) }}" class="mt-3">
                    <select name="praticien_id" class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-blue-500 focus:ring-blue-200" onchange="this.form.submit()">
                        @foreach($praticiens as $praticien)
                            <option value="{{ $praticien->id }}" {{ optional($selectedPraticien)->id === $praticien->id ? 'selected' : '' }}>
                                Dr. {{ $praticien->user->nom_complet }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <div class="xl:col-span-2">
            <div class="rounded-2xl border border-gray-100 bg-white p-6 shadow-sm" x-data="reprogAgenda({
                    slots: @json($slotsByDay),
                    busy: @json($busyByDay),
                    selectedSlot: '{{ old('date_heure_rdv', optional($existingRdv)->date_heure_rdv?->format('Y-m-d\TH:i') ?? '') }}',
                    manualDate: '{{ old('date_heure_rdv') ? substr(old('date_heure_rdv'), 0, 10) : (optional(optional($existingRdv)->date_heure_rdv)->format('Y-m-d') ?? '') }}',
                    manualTime: '{{ old('date_heure_rdv') ? substr(old('date_heure_rdv'), 11, 5) : (optional(optional($existingRdv)->date_heure_rdv)->format('H:i') ?? '') }}',
                    duration: '{{ old('duree', $existingRdv->duree ?? 30) }}',
                })">
                <form method="POST" action="{{ route('secretaire.demande.reprogrammation.store', $demande) }}" class="space-y-6">
                    @csrf
                    <input type="hidden" name="praticien_id" value="{{ optional($selectedPraticien)->id }}">
                    <input type="hidden" name="date_heure_rdv" x-model="selectedSlot">

                    <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                        <div>
                            <h2 class="text-xl font-semibold text-gray-900">Agenda visuel</h2>
                            <p class="text-sm text-gray-500">Cliquez sur un créneau vert pour le sélectionner, ou renseignez manuellement une date.</p>
                        </div>
                        <div class="flex items-center gap-3 text-sm">
                            <span class="inline-flex items-center gap-2 rounded-full bg-green-100 px-3 py-1 text-green-700"><span class="h-2 w-2 rounded-full bg-green-500"></span>Disponible</span>
                            <span class="inline-flex items-center gap-2 rounded-full bg-red-100 px-3 py-1 text-red-700"><span class="h-2 w-2 rounded-full bg-red-500"></span>Occupé</span>
                        </div>
                    </div>

                    <div class="grid gap-4 lg:grid-cols-2">
                        <template x-for="day in slots" :key="day.date">
                            <div class="rounded-xl border border-gray-200 p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <p class="text-sm font-semibold text-gray-800" x-text="day.label"></p>
                                    <span class="text-xs text-gray-500" x-text="formatDateBadge(day.date)"></span>
                                </div>
                                <div class="flex flex-wrap gap-2">
                                    <template x-if="day.slots.length">
                                        <template x-for="slot in day.slots" :key="slot.value">
                                            <button type="button" @click="selectSlot(slot.value)" :class="slotClass(slot.value)" x-text="formatHour(slot.value)"></button>
                                        </template>
                                    </template>
                                    <template x-if="!day.slots.length">
                                        <p class="text-xs text-gray-500 italic">Pas de créneaux automatiquement disponibles pour cette journée.</p>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>

                    <div x-show="!slots.length" class="rounded-xl border border-yellow-200 bg-yellow-50 p-4 text-sm text-yellow-700">
                        Aucun créneau disponible n'a été trouvé sur cette période. Utilisez la saisie manuelle ci-dessous pour forcer la reprogrammation.
                    </div>

                    <div class="rounded-xl border border-gray-200 p-4">
                        <h3 class="text-sm font-semibold text-gray-800">Saisie manuelle</h3>
                        <div class="mt-3 grid gap-3 sm:grid-cols-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Date</label>
                                <input type="date" x-model="manualDate" min="{{ now()->format('Y-m-d') }}" class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-purple-500 focus:ring-purple-200">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Heure</label>
                                <input type="time" x-model="manualTime" class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-purple-500 focus:ring-purple-200">
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-600 mb-1">Durée (min)</label>
                                <select name="duree" x-model="duration" class="w-full rounded-lg border-gray-300 px-3 py-2 text-sm focus:border-purple-500 focus:ring-purple-200">
                                    @foreach([15, 30, 45, 60, 75, 90, 120] as $duree)
                                        <option value="{{ $duree }}">{{ $duree }} minutes</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div x-show="manualFeedback.message" x-cloak class="mt-3 rounded-lg px-3 py-2 text-xs" :class="manualFeedback.classes">
                            <span x-text="manualFeedback.message"></span>
                        </div>
                    </div>

                    @if($errors->any())
                        <div class="rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700">
                            <ul class="list-disc pl-5 space-y-1">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="flex items-center justify-between">
                        <p class="text-sm text-gray-500">Créneau sélectionné : <span class="font-semibold text-gray-800" x-text="selectedSlot ? displaySelection(selectedSlot) : 'aucun' "></span></p>
                        <button type="submit" class="inline-flex items-center gap-2 rounded-lg bg-gradient-to-r from-purple-600 to-blue-600 px-5 py-2 text-sm font-semibold text-white shadow-md transition hover:opacity-90 disabled:opacity-50" :disabled="!selectedSlot || manualFeedback.state === 'error'">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Valider la reprogrammation
                        </button>
                    </div>
                </form>

                <div class="mt-8">
                    <h3 class="text-sm font-semibold text-gray-800 mb-3">Rendez-vous déjà planifiés</h3>
                    <div class="grid gap-3 md:grid-cols-2">
                        @forelse($busyByDay as $day)
                            <div class="rounded-xl border border-gray-200 p-4">
                                <p class="text-sm font-semibold text-gray-800">{{ $day['label'] }}</p>
                                <ul class="mt-2 space-y-1 text-xs text-gray-600">
                                    @foreach($day['rdvs'] as $rdv)
                                        <li class="flex items-center gap-2">
                                            <span class="h-2 w-2 rounded-full bg-red-500"></span>
                                            {{ $rdv['label'] }}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">Aucun rendez-vous existant sur la période consultée.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function reprogAgenda(initialState) {
        return {
            slots: initialState.slots || [],
            busy: initialState.busy || [],
            selectedSlot: initialState.selectedSlot || '',
            manualDate: initialState.manualDate || '',
            manualTime: initialState.manualTime || '',
            duration: initialState.duration || '30',
            manualFeedback: { state: '', message: '', classes: '' },

            init() {
                this.updateManualFromSelected();

                this.$watch('manualDate', () => this.onManualChange());
                this.$watch('manualTime', () => this.onManualChange());
                this.$watch('duration', () => this.onManualChange());
            },

            selectSlot(slot) {
                this.selectedSlot = slot;
                this.manualDate = slot.slice(0, 10);
                this.manualTime = slot.slice(11, 16);
                this.manualFeedback = { state: 'ok', message: 'Créneau disponible sélectionné.', classes: 'bg-green-50 border border-green-200 text-green-700' };
            },

            slotClass(slot) {
                const base = 'px-3 py-1 rounded-lg text-xs font-semibold transition';
                const isSelected = this.selectedSlot === slot;
                return `${base} ${isSelected ? 'bg-gradient-to-r from-purple-600 to-blue-600 text-white shadow-lg' : 'bg-green-100 text-green-700 hover:bg-green-200'}`;
            },

            formatHour(slot) {
                return slot.slice(11, 16);
            },

            formatDateBadge(date) {
                const formatter = new Intl.DateTimeFormat('fr-FR', { day: 'numeric', month: 'short' });
                return formatter.format(new Date(date));
            },

            displaySelection(slot) {
                if (!slot) {
                    return 'aucun';
                }
                const date = new Date(slot);
                if (Number.isNaN(date.getTime())) {
                    return 'aucun';
                }
                return date.toLocaleDateString('fr-FR', { weekday: 'long', day: '2-digit', month: 'long' }) + ' à ' + slot.slice(11, 16);
            },

            onManualChange() {
                const slot = this.manualDate && this.manualTime ? `${this.manualDate}T${this.manualTime}` : '';
                if (!slot) {
                    this.selectedSlot = '';
                    this.manualFeedback = { state: '', message: '', classes: '' };
                    return;
                }

                this.selectedSlot = slot;
                const verdict = this.evaluateManualSlot(slot);
                this.manualFeedback = verdict;
            },

            evaluateManualSlot(slot) {
                const date = new Date(slot);
                if (Number.isNaN(date.getTime())) {
                    return { state: 'error', message: 'Date ou heure invalide.', classes: 'bg-red-50 border border-red-200 text-red-700' };
                }

                if (date <= new Date()) {
                    return { state: 'error', message: 'Le créneau doit être dans le futur.', classes: 'bg-red-50 border border-red-200 text-red-700' };
                }

                const minutes = Number(this.duration || 0);
                if (!minutes) {
                    return { state: 'error', message: 'Durée invalide.', classes: 'bg-red-50 border border-red-200 text-red-700' };
                }

                const endDate = new Date(date.getTime() + minutes * 60000);

                const dayKey = ['dimanche','lundi','mardi','mercredi','jeudi','vendredi','samedi'][date.getDay()].toUpperCase();

                const hasConflict = (this.busy || []).some(day => day.rdvs.some(rdv => {
                    const start = new Date(rdv.start);
                    const end = new Date(rdv.end);
                    return date < end && endDate > start;
                }));

                if (hasConflict) {
                    return { state: 'error', message: 'Créneau déjà occupé. Choisissez une autre heure.', classes: 'bg-red-50 border border-red-200 text-red-700' };
                }

                return { state: 'ok', message: 'Créneau valide et disponible.', classes: 'bg-green-50 border border-green-200 text-green-700' };
            },

            updateManualFromSelected() {
                if (!this.selectedSlot) {
                    return;
                }
                this.manualDate = this.selectedSlot.slice(0, 10);
                this.manualTime = this.selectedSlot.slice(11, 16);
                this.manualFeedback = { state: 'ok', message: 'Créneau sélectionné.', classes: 'bg-green-50 border border-green-200 text-green-700' };
            },
        };
    }
</script>
@endpush
