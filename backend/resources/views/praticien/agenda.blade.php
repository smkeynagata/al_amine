@extends('praticien.layouts.app')

@section('title', 'Mon Agenda')

@section('extra-css')
<style>
    [x-cloak] {
        display: none !important;
    }
</style>
@endsection

@section('content')
@if (session('success'))
    <div class="mb-6 flex items-start gap-3 rounded-2xl border border-green-200 bg-green-50 p-4 text-sm text-green-700 shadow-sm">
        <span class="mt-0.5 inline-flex h-6 w-6 items-center justify-center rounded-full bg-green-600 text-white">✓</span>
        <div>
            <p class="font-semibold">{{ session('success') }}</p>
            <p class="text-xs text-green-600">Informez le patient par le canal habituel si nécessaire.</p>
        </div>
    </div>
@endif

@if ($errors->any())
    <div class="mb-6 rounded-2xl border border-red-200 bg-red-50 p-4 text-sm text-red-700 shadow-sm">
        <p class="font-semibold mb-1">Reprogrammation impossible</p>
        <ul class="list-disc space-y-1 pl-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<!-- Header with Actions -->
<div class="flex items-center justify-between mb-8">
    <div>
        <h1 class="text-3xl font-bold text-gray-900">Mon Agenda</h1>
        <p class="text-gray-600 mt-1">Gérez vos rendez-vous et disponibilités</p>
    </div>
    <div class="flex items-center space-x-3">
        <a href="{{ route('praticien.disponibilites') }}" class="bg-gradient-to-r from-purple-600 to-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:opacity-90 transition flex items-center space-x-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Gérer mes disponibilités</span>
        </a>
    </div>
</div>

<!-- View Toggle -->
<div class="flex gap-2 mb-6">
    <button onclick="changeView('day')" class="view-btn-day px-4 py-2 bg-white text-gray-700 border-2 border-purple-600 rounded-lg font-semibold hover:bg-purple-50 transition">
        📅 Jour
    </button>
    <button onclick="changeView('week')" class="view-btn-week px-4 py-2 bg-gray-100 text-gray-600 rounded-lg font-semibold hover:bg-gray-200 transition">
        📆 Semaine
    </button>
    <button onclick="changeView('month')" class="view-btn-month px-4 py-2 bg-gray-100 text-gray-600 rounded-lg font-semibold hover:bg-gray-200 transition">
        📋 Mois
    </button>
</div>

<div class="grid grid-cols-12 gap-6">
    <!-- Left Column - Main Calendar -->
    <div class="col-span-8">
        <div class="bg-white rounded-2xl shadow-elegant p-6">
            <!-- Calendar Header -->
            <div class="flex items-center justify-between mb-6">
                <button onclick="previousMonth()" class="w-10 h-10 hover:bg-gray-100 rounded-lg transition flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
                <h2 class="text-2xl font-bold text-gray-900">{{ now()->locale('fr')->isoFormat('MMMM YYYY') }}</h2>
                <button onclick="nextMonth()" class="w-10 h-10 hover:bg-gray-100 rounded-lg transition flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            </div>

            <!-- Professional Weekly Schedule / Emploi du temps -->
            <div class="overflow-x-auto">
                <div class="min-w-max bg-white rounded-xl overflow-hidden">
                    <!-- Header with Days -->
                    <div class="grid grid-cols-8 border-b border-gray-200">
                        <!-- Time column header -->
                        <div class="col-span-1 bg-gray-50 border-r border-gray-200 p-4 font-semibold text-gray-700 text-sm"></div>

                        <!-- Day headers -->
                        @php
                            $daysOfWeek = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
                            $startOfWeek = now()->startOfWeek();
                        @endphp

                        @foreach($daysOfWeek as $index => $day)
                            @php
                                $currentDate = $startOfWeek->copy()->addDays($index);
                                $isToday = $currentDate->isSameDay(now());
                            @endphp
                            <div class="col-span-1 bg-gradient-to-b {{ $isToday ? 'from-purple-100 to-blue-100' : 'from-gray-50 to-white' }} border-r border-gray-200 p-4 text-center">
                                <p class="text-sm font-bold {{ $isToday ? 'text-purple-700' : 'text-gray-700' }}">{{ $day }}</p>
                                <p class="text-xs {{ $isToday ? 'text-purple-600 font-semibold' : 'text-gray-500' }}">{{ $currentDate->format('d/m') }}</p>
                            </div>
                        @endforeach
                    </div>

                    <!-- Time slots -->
                    @php
                        $hours = range(8, 18);
                    @endphp

                    @foreach($hours as $hour)
                        <div class="grid grid-cols-8 border-b border-gray-100 hover:bg-blue-50 transition">
                            <!-- Time label -->
                            <div class="col-span-1 bg-gray-50 border-r border-gray-200 p-4 text-sm font-semibold text-gray-600 flex items-center justify-center">
                                {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}:00
                            </div>

                            <!-- Slots for each day -->
                            @foreach($daysOfWeek as $dayIndex => $day)
                                @php
                                    $currentDate = $startOfWeek->copy()->addDays($dayIndex);
                                    $rdvsForSlot = $rendezVous->filter(function($rdv) use ($currentDate, $hour) {
                                        return $rdv->date_heure_rdv->isSameDay($currentDate)
                                               && $rdv->date_heure_rdv->hour === $hour;
                                    });
                                @endphp

                                <div class="col-span-1 border-r border-gray-200 p-2 relative min-h-24">
                                    @foreach($rdvsForSlot as $rdv)
                                        <a href="{{ route('praticien.consultation.show', $rdv) }}"
                                           class="group absolute inset-0 top-2 bottom-0 mx-1 px-2 py-1 rounded-lg text-xs font-semibold text-white transition transform hover:scale-105 cursor-pointer
                                           @if($rdv->statut === 'CONFIRME') bg-gradient-to-br from-green-500 to-emerald-600 shadow-md
                                           @elseif($rdv->statut === 'PLANIFIE') bg-gradient-to-br from-yellow-500 to-orange-600 shadow-md
                                           @elseif($rdv->statut === 'EN_COURS') bg-gradient-to-br from-blue-500 to-cyan-600 shadow-md
                                           @else bg-gradient-to-br from-gray-500 to-slate-600 shadow-md
                                           @endif"
                                           title="{{ $rdv->patient->user->nom_complet }} - {{ $rdv->date_heure_rdv->format('H:i') }}">
                                            <div class="flex flex-col h-full justify-center">
                                                <p class="font-bold leading-tight truncate">
                                                    {{ substr($rdv->patient->user->prenom, 0, 1) }}. {{ substr($rdv->patient->user->name, 0, 8) }}
                                                </p>
                                                <p class="text-xs opacity-90">{{ $rdv->date_heure_rdv->format('H:i') }}</p>
                                            </div>

                                            <!-- Tooltip on hover -->
                                            <div class="absolute bottom-full left-0 mb-2 hidden group-hover:block bg-gray-900 text-white px-3 py-2 rounded-lg shadow-lg whitespace-nowrap text-xs z-10">
                                                <p class="font-bold">{{ $rdv->patient->user->nom_complet }}</p>
                                                <p>{{ $rdv->date_heure_rdv->format('H:i') }} ({{ $rdv->duree }}min)</p>
                                                <p class="mt-1">{{ $rdv->statut }}</p>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Legend -->
            <div class="mt-6 flex flex-wrap gap-4 justify-center p-4 bg-gray-50 rounded-lg">
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 rounded-full bg-gradient-to-br from-green-500 to-emerald-600"></div>
                    <span class="text-sm text-gray-700">Confirmé</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 rounded-full bg-gradient-to-br from-yellow-500 to-orange-600"></div>
                    <span class="text-sm text-gray-700">Planifié</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 rounded-full bg-gradient-to-br from-blue-500 to-cyan-600"></div>
                    <span class="text-sm text-gray-700">En cours</span>
                </div>
                <div class="flex items-center space-x-2">
                    <div class="w-3 h-3 rounded-full bg-gradient-to-br from-gray-500 to-slate-600"></div>
                    <span class="text-sm text-gray-700">Terminé</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Sidebar - Quick Stats & Legend -->
    <div class="col-span-4 space-y-6">
        <!-- Statistics Card -->
        <div class="bg-white rounded-2xl shadow-elegant p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">📊 Statistiques</h3>

            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-gradient-to-r from-purple-50 to-blue-50 rounded-lg">
                    <span class="text-sm text-gray-600">Total RDV</span>
                    <span class="text-2xl font-bold text-purple-600">{{ $rendezVous->count() }}</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-gradient-to-r from-green-50 to-emerald-50 rounded-lg">
                    <span class="text-sm text-gray-600">Confirmés</span>
                    <span class="text-2xl font-bold text-green-600">{{ $rendezVous->where('statut', 'CONFIRME')->count() }}</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-gradient-to-r from-yellow-50 to-orange-50 rounded-lg">
                    <span class="text-sm text-gray-600">En attente</span>
                    <span class="text-2xl font-bold text-yellow-600">{{ $rendezVous->where('statut', 'PLANIFIE')->count() }}</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-gradient-to-r from-blue-50 to-cyan-50 rounded-lg">
                    <span class="text-sm text-gray-600">En cours</span>
                    <span class="text-2xl font-bold text-blue-600">{{ $rendezVous->where('statut', 'EN_COURS')->count() }}</span>
                </div>

                <div class="flex items-center justify-between p-3 bg-gradient-to-r from-gray-50 to-slate-50 rounded-lg">
                    <span class="text-sm text-gray-600">Terminés</span>
                    <span class="text-2xl font-bold text-gray-600">{{ $rendezVous->where('statut', 'TERMINE')->count() }}</span>
                </div>
            </div>
        </div>

        <!-- Reschedule Card -->
        <div class="bg-white rounded-2xl shadow-elegant p-6">
            <h3 class="text-lg font-bold text-gray-900">🔁 Reprogrammer un rendez-vous</h3>
            <p class="text-sm text-gray-500 mt-1 mb-4">Sélectionnez un rendez-vous à venir pour modifier sa date ou son horaire.</p>

            <div class="space-y-4 max-h-96 overflow-y-auto pr-1">
                @forelse($prochainsRdv as $rdv)
                    <div x-data="{ open: false }" class="border border-gray-100 rounded-xl p-4 bg-gradient-to-r from-white via-white to-gray-50 appointment-card">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">{{ $rdv->patient->user->nom_complet }}</p>
                                <p class="text-xs text-gray-500 mt-1">
                                    {{ $rdv->date_heure_rdv->locale('fr')->isoFormat('dddd D MMMM YYYY') }} • {{ $rdv->date_heure_rdv->format('H:i') }}
                                </p>
                                <span class="mt-2 inline-flex items-center rounded-md px-2 py-0.5 text-[11px] font-semibold {{ getStatutBadgeClass($rdv->statut) }}">
                                    {{ ucfirst(strtolower($rdv->statut)) }}
                                </span>
                                @if ($rdv->notes)
                                    <p class="mt-2 text-xs italic text-gray-500">{{ $rdv->notes }}</p>
                                @endif
                            </div>
                            <button type="button" @click="open = true" class="text-sm font-semibold text-purple-600 hover:text-purple-800 transition">
                                Reprogrammer
                            </button>
                        </div>

                        <div x-show="open" x-cloak class="fixed inset-0 z-50 flex items-center justify-center px-4">
                            <div class="absolute inset-0 bg-black bg-opacity-40" @click="open = false"></div>

                            <div class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
                                <div class="mb-4 flex items-center justify-between">
                                    <h4 class="text-lg font-bold text-gray-900">Reprogrammer le rendez-vous</h4>
                                    <button type="button" @click="open = false" class="text-gray-400 transition hover:text-gray-600">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </div>

                                <form method="POST" action="{{ route('praticien.rendezvous.reschedule', $rdv) }}" class="space-y-4">
                                    @csrf
                                    @method('PATCH')

                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-gray-700">Nouvelle date</label>
                                        <input type="date" name="date" value="{{ old('date', $rdv->date_heure_rdv->format('Y-m-d')) }}" min="{{ now()->format('Y-m-d') }}" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                                    </div>

                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-gray-700">Horaire</label>
                                        <input type="time" name="heure" value="{{ old('heure', $rdv->date_heure_rdv->format('H:i')) }}" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200" required>
                                    </div>

                                    <div>
                                        <label class="mb-1 block text-sm font-medium text-gray-700">Motif (optionnel)</label>
                                        <textarea name="motif" rows="3" class="w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-purple-500 focus:outline-none focus:ring-2 focus:ring-purple-200" placeholder="Ex: Patient indisponible, déplacement d'urgence">{{ old('motif', $rdv->notes) }}</textarea>
                                    </div>

                                    <div class="flex justify-end gap-2 pt-2">
                                        <button type="button" @click="open = false" class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-600 transition hover:bg-gray-50">Annuler</button>
                                        <button type="submit" class="rounded-lg bg-gradient-to-r from-purple-600 to-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:opacity-90">Confirmer</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">Aucun rendez-vous à venir disponible pour reprogrammation.</p>
                @endforelse
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl shadow-elegant p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">⚡ Actions rapides</h3>
            <div class="space-y-2">
                <a href="{{ route('praticien.disponibilites') }}" class="block w-full bg-gradient-to-r from-purple-600 to-blue-600 text-white px-4 py-3 rounded-lg font-semibold hover:opacity-90 transition text-center">
                    + Disponibilités
                </a>
                <a href="{{ route('praticien.patients') }}" class="block w-full bg-gray-100 text-gray-700 px-4 py-3 rounded-lg font-semibold hover:bg-gray-200 transition text-center">
                    👥 Mes patients
                </a>
                <a href="{{ route('praticien.consultations') }}" class="block w-full bg-gray-100 text-gray-700 px-4 py-3 rounded-lg font-semibold hover:bg-gray-200 transition text-center">
                    📋 Consultations
                </a>
            </div>
        </div>

        <!-- Legend -->
        <div class="bg-white rounded-2xl shadow-elegant p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-4">🎨 Légende</h3>
            <div class="space-y-3">
                <div class="flex items-center space-x-3">
                    <span class="status-indicator status-confirmed">✓</span>
                    <span class="text-sm text-gray-600">Confirmé</span>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="status-indicator status-planned">⏱</span>
                    <span class="text-sm text-gray-600">Planifié</span>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="status-indicator status-ongoing">►</span>
                    <span class="text-sm text-gray-600">En cours</span>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="legend-dot" style="background-color: #e5e7eb;"></span>
                    <span class="text-sm text-gray-600">Autre statut</span>
                </div>
            </div>
        </div>

        <!-- Today's Summary -->
        <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-2xl shadow-elegant p-6 text-white">
            <h3 class="text-lg font-bold mb-4">📅 Aujourd'hui</h3>
            <div class="space-y-2 text-sm">
                <p>{{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</p>
                <div class="bg-white bg-opacity-20 rounded-lg p-3 mt-4">
                    <p class="font-semibold">{{ $rdvAujourdhui->count() }} rendez-vous prévu(s)</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    function changeView(view) {
        document.querySelectorAll('[class*="view-btn-"]').forEach(btn => {
            btn.classList.remove('bg-white', 'text-gray-700', 'border-2', 'border-purple-600');
            btn.classList.add('bg-gray-100', 'text-gray-600');
        });

        document.querySelector('.view-btn-' + view).classList.remove('bg-gray-100', 'text-gray-600');
        document.querySelector('.view-btn-' + view).classList.add('bg-white', 'text-gray-700', 'border-2', 'border-purple-600');
    }

    function previousMonth() {
        // Logic pour le mois précédent
        console.log('Previous month');
    }

    function nextMonth() {
        // Logic pour le mois suivant
        console.log('Next month');
    }

    function showDetails(rdvId) {
        console.log('Show details for RDV:', rdvId);
    }
</script>
@endsection

