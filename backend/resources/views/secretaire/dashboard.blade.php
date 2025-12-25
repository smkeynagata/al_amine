@extends('secretaire.layouts.app')

@section('title', 'Tableau de bord')

@section('content')
<div class="relative mb-8 overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 via-blue-700 to-blue-900 p-8 text-white shadow-lg">
    <div class="relative z-10 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <div class="mb-3 flex items-center gap-3">
                <div class="rounded-lg bg-white/20 p-3">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold">Tableau de bord</h1>
            </div>
            <p class="text-blue-100">Bienvenue {{ auth()->user()->prenom }} {{ auth()->user()->name }}. Consultez vos indicateurs clés et vos actions prioritaires.</p>
        </div>
        <div class="rounded-xl bg-white/10 px-4 py-3 text-sm font-medium text-blue-100">
            {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
        </div>
    </div>
</div>

<div class="mb-8 grid grid-cols-1 gap-4 md:grid-cols-4">
    <div class="rounded-xl border border-blue-200 bg-gradient-to-br from-blue-50 to-blue-100 p-5 shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-blue-600">Demandes en attente</p>
                <p class="mt-2 text-3xl font-bold text-blue-900">{{ $stats['demandes_attente'] ?? 0 }}</p>
                <p class="mt-1 text-xs text-blue-500">À traiter rapidement</p>
            </div>
            <div class="rounded-lg bg-blue-200 p-3">
                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="rounded-xl border border-green-200 bg-gradient-to-br from-green-50 to-green-100 p-5 shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-green-600">RDV aujourd'hui</p>
                <p class="mt-2 text-3xl font-bold text-green-900">{{ $stats['rdv_aujourdhui'] ?? 0 }}</p>
                <p class="mt-1 text-xs text-green-500">Patients attendus</p>
            </div>
            <div class="rounded-lg bg-green-200 p-3">
                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="rounded-xl border border-purple-200 bg-gradient-to-br from-purple-50 to-purple-100 p-5 shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-purple-600">Factures impayées</p>
                <p class="mt-2 text-3xl font-bold text-purple-900">{{ $stats['factures_impayees'] ?? 0 }}</p>
                <p class="mt-1 text-xs text-purple-500">Relances à planifier</p>
            </div>
            <div class="rounded-lg bg-purple-200 p-3">
                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
        </div>
    </div>

    <div class="rounded-xl border border-orange-200 bg-gradient-to-br from-orange-50 to-orange-100 p-5 shadow-md transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-wide text-orange-600">Paiements aujourd'hui</p>
                <p class="mt-2 text-3xl font-bold text-orange-900">{{ $stats['paiements_aujourdhui'] ?? 0 }}</p>
                <p class="mt-1 text-xs text-orange-500">Encaissements du jour</p>
            </div>
            <div class="rounded-lg bg-orange-200 p-3">
                <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
    </div>
</div>

<div class="mb-8 grid grid-cols-1 gap-6 md:grid-cols-3">
    <a href="{{ route('secretaire.file-attente', ['statut' => 'EN_ATTENTE']) }}" class="group rounded-xl bg-gradient-to-r from-blue-600 to-blue-800 p-6 text-white shadow-lg transition transform hover:-translate-y-1 hover:shadow-xl">
        <div class="mx-auto mb-3 w-fit rounded-lg bg-white/20 p-3 transition-colors group-hover:bg-white/30">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        </div>
        <h3 class="text-center text-lg font-bold">File d'attente</h3>
        <p class="mt-2 text-center text-sm text-blue-100">Suivre les demandes en cours</p>
    </a>

    <a href="{{ route('secretaire.agendas') }}" class="group rounded-xl bg-gradient-to-r from-green-600 to-green-800 p-6 text-white shadow-lg transition transform hover:-translate-y-1 hover:shadow-xl">
        <div class="mx-auto mb-3 w-fit rounded-lg bg-white/20 p-3 transition-colors group-hover:bg-white/30">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
        </div>
        <h3 class="text-center text-lg font-bold">Agendas</h3>
        <p class="mt-2 text-center text-sm text-green-100">Coordonner les plannings</p>
    </a>

    <a href="{{ route('secretaire.facturation') }}" class="group rounded-xl bg-gradient-to-r from-purple-600 to-purple-800 p-6 text-white shadow-lg transition transform hover:-translate-y-1 hover:shadow-xl">
        <div class="mx-auto mb-3 w-fit rounded-lg bg-white/20 p-3 transition-colors group-hover:bg-white/30">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>
        <h3 class="text-center text-lg font-bold">Facturation</h3>
        <p class="mt-2 text-center text-sm text-purple-100">Générer et gérer les factures</p>
    </a>
</div>

<div class="mb-6 grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-md transition-shadow duration-200 hover:shadow-lg lg:col-span-2">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="flex items-center gap-2 text-xl font-bold text-gray-800">
                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Activité hebdomadaire
            </h2>
            <span class="rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">Vue synthétique</span>
        </div>
        <div class="flex h-64 items-center justify-center rounded-lg bg-gray-50">
            <canvas id="activityChart"></canvas>
        </div>
        <div class="mt-6 grid grid-cols-2 gap-4 text-sm md:grid-cols-4">
            <div class="rounded-lg bg-blue-50 p-4 text-center">
                <div class="text-lg font-bold text-blue-700">{{ $stats['demandes_attente'] ?? 0 }}</div>
                <div class="mt-1 text-xs text-blue-600">Demandes</div>
            </div>
            <div class="rounded-lg bg-green-50 p-4 text-center">
                <div class="text-lg font-bold text-green-700">{{ $stats['rdv_aujourdhui'] ?? 0 }}</div>
                <div class="mt-1 text-xs text-green-600">RDV</div>
            </div>
            <div class="rounded-lg bg-purple-50 p-4 text-center">
                <div class="text-lg font-bold text-purple-700">{{ $stats['factures_impayees'] ?? 0 }}</div>
                <div class="mt-1 text-xs text-purple-600">Impayées</div>
            </div>
            <div class="rounded-lg bg-orange-50 p-4 text-center">
                <div class="text-lg font-bold text-orange-700">{{ $stats['paiements_aujourdhui'] ?? 0 }}</div>
                <div class="mt-1 text-xs text-orange-600">Paiements</div>
            </div>
        </div>
    </div>

    <div class="space-y-6">
        <div class="rounded-xl border border-gray-200 bg-white p-6 text-gray-800 shadow-md transition-shadow duration-200 hover:shadow-lg">
            <h3 class="mb-4 flex items-center gap-2 text-lg font-bold">
                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                </svg>
                Résumé rapide
            </h3>
            <div class="space-y-4 text-sm">
                <div class="rounded-lg border border-blue-100 bg-blue-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-blue-600">Demandes traitées</p>
                    <p class="mt-2 text-2xl font-bold text-blue-900">{{ $stats['demandes_traitees'] ?? 0 }}</p>
                </div>
                <div class="rounded-lg border border-green-100 bg-green-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-green-600">Taux de validation</p>
                    <p class="mt-2 text-2xl font-bold text-green-900">{{ $stats['taux_validation'] ?? 85 }}%</p>
                </div>
                <div class="rounded-lg border border-purple-100 bg-purple-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-wide text-purple-600">Satisfaction estimée</p>
                    <p class="mt-2 text-2xl font-bold text-purple-900">{{ $stats['taux_satisfaction'] ?? 95 }}%</p>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 text-gray-800 shadow-md transition-shadow duration-200 hover:shadow-lg">
            <h3 class="mb-4 flex items-center gap-2 text-lg font-bold">
                <svg class="h-5 w-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 8v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Conseil du jour
            </h3>
            <p class="text-sm text-gray-600">Préparez vos relances avant 10h pour maximiser les confirmations de rendez-vous.</p>
            <p class="mt-4 text-xs font-semibold text-orange-500">Mis à jour il y a 2h</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
    <div class="lg:col-span-2 rounded-xl border border-gray-200 bg-white p-6 shadow-md transition-shadow duration-200 hover:shadow-lg">
        <div class="mb-4 flex items-center justify-between">
            <h2 class="flex items-center gap-2 text-xl font-bold text-gray-800">
                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Demandes récentes
            </h2>
            <a href="{{ route('secretaire.file-attente') }}" class="inline-flex items-center gap-2 rounded-lg border border-blue-200 px-4 py-2 text-sm font-semibold text-blue-700 transition hover:bg-blue-50">
                Voir tout
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        @if(isset($demandesRecentes) && $demandesRecentes->isNotEmpty())
            <div class="space-y-4">
                @foreach($demandesRecentes as $demande)
                    <div class="flex items-center gap-4 rounded-xl border border-blue-100 bg-blue-50/50 p-4 transition hover:border-blue-200 hover:bg-blue-50">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-blue-100 text-blue-700 font-semibold">
                            {{ substr($demande->patient->user->prenom, 0, 1) }}{{ substr($demande->patient->user->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <p class="font-semibold text-gray-800">{{ $demande->patient->user->nom_complet }}</p>
                            <p class="mt-1 flex items-center gap-3 text-sm text-gray-500">
                                <span class="flex items-center gap-1">
                                    <svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Dr. {{ $demande->praticien->user->nom_complet }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    {{ $demande->date_heure_souhaitee->format('d/m/Y \à H:i') }}
                                </span>
                                <span class="text-xs uppercase tracking-wide text-blue-600">{{ $demande->specialite->nom }}</span>
                            </p>
                        </div>
                        <a href="{{ route('secretaire.file-attente') }}" class="rounded-lg bg-blue-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-blue-700">Traiter</a>
                    </div>
                @endforeach
            </div>
        @else
            <div class="rounded-xl border border-dashed border-gray-200 bg-gray-50 py-12 text-center text-sm text-gray-500">
                Aucune nouvelle demande pour le moment.
            </div>
        @endif
    </div>

    <div class="space-y-6">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-md transition-shadow duration-200 hover:shadow-lg">
            <h3 class="mb-4 flex items-center gap-2 text-lg font-bold text-gray-800">
                <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                RDV aujourd'hui
            </h3>
            @if(isset($rdvAujourdhui) && $rdvAujourdhui->isNotEmpty())
                <div class="space-y-3">
                    @foreach($rdvAujourdhui as $rdv)
                        <div class="rounded-lg border border-green-100 bg-green-50/50 p-4">
                            <p class="font-semibold text-gray-800">{{ $rdv->patient->user->nom_complet }}</p>
                            <p class="mt-1 text-sm text-gray-500">
                                {{ $rdv->date_heure_rdv->format('H:i') }} • Dr. {{ $rdv->praticien->user->nom_complet }}
                            </p>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="rounded-lg border border-dashed border-gray-200 bg-gray-50 py-6 text-center text-sm text-gray-500">Aucun rendez-vous prévu aujourd'hui.</p>
            @endif
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-md transition-shadow duration-200 hover:shadow-lg">
            <h3 class="mb-4 flex items-center gap-2 text-lg font-bold text-gray-800">
                <svg class="h-5 w-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
                Mon profil
            </h3>
            <div class="text-center">
                <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-full bg-indigo-100 text-lg font-semibold text-indigo-700">
                    {{ substr(auth()->user()->prenom, 0, 1) }}{{ substr(auth()->user()->name, 0, 1) }}
                </div>
                <p class="text-base font-semibold text-gray-800">{{ auth()->user()->nom_complet }}</p>
                <p class="mt-1 text-sm text-gray-500">{{ auth()->user()->email }}</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const ctx = document.getElementById('activityChart');
    if (ctx) {
        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 250);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.4)');
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0.05)');

        const gradientGreen = ctx.getContext('2d').createLinearGradient(0, 0, 0, 250);
        gradientGreen.addColorStop(0, 'rgba(34, 197, 94, 0.4)');
        gradientGreen.addColorStop(1, 'rgba(34, 197, 94, 0.05)');

        const gradientOrange = ctx.getContext('2d').createLinearGradient(0, 0, 0, 250);
        gradientOrange.addColorStop(0, 'rgba(249, 115, 22, 0.4)');
        gradientOrange.addColorStop(1, 'rgba(249, 115, 22, 0.05)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($stats['chart_labels']),
                datasets: [
                    {
                        label: 'Demandes',
                        data: @json($stats['chart_demandes']),
                        borderColor: 'rgba(59, 130, 246, 1)',
                        backgroundColor: gradient,
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: 'rgba(59, 130, 246, 1)',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'RDV',
                        data: @json($stats['chart_rdvs']),
                        borderColor: 'rgba(34, 197, 94, 1)',
                        backgroundColor: gradientGreen,
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: 'rgba(34, 197, 94, 1)',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Paiements',
                        data: @json($stats['chart_paiements']),
                        borderColor: 'rgba(249, 115, 22, 1)',
                        backgroundColor: gradientOrange,
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: 'rgba(249, 115, 22, 1)',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: { 
                        display: true,
                        position: 'top',
                        labels: {
                            usePointStyle: true,
                            padding: 15,
                            font: { size: 12, weight: '600' }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(31, 41, 55, 0.95)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 12,
                        borderRadius: 8,
                        displayColors: true,
                        callbacks: {
                            title: function(context) {
                                return context[0].label;
                            },
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y;
                            }
                        }
                    }
                },
                interaction: { mode: 'index', intersect: false },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { color: '#6b7280', font: { size: 11 } }
                    },
                    y: {
                        grid: { color: 'rgba(148, 163, 184, 0.2)' },
                        ticks: { color: '#6b7280', beginAtZero: true, precision: 0 }
                    }
                }
            }
        });
    }
</script>
@endsection
