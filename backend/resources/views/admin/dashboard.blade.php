@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page-title', 'Tableau de bord - Administrateur')
@section('breadcrumb', 'Accueil')

@section('sidebar')
@include('admin.partials.sidebar')
@endsection

@section('content')
<!-- Header professionnel -->
<div class="relative mb-8 overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 via-blue-700 to-blue-900 p-8 shadow-lg">
    <div class="absolute inset-0 opacity-0"></div>
    <div class="relative z-10 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white">Tableau de bord</h1>
            </div>
            <p class="text-blue-100">Bienvenue sur votre tableau de bord administrateur. Retrouvez l'ensemble de vos statistiques et indicateurs clés.</p>
        </div>
    </div>
</div>

<!-- Statistics Cards avec design amélioré -->
<div class="grid grid-cols-1 gap-4 md:grid-cols-4 mb-8">
    <div class="rounded-xl border border-blue-200 bg-gradient-to-br from-blue-50 to-blue-100 p-5 shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-blue-600">Total Utilisateurs</p>
                <p class="mt-2 text-3xl font-bold text-blue-900">{{ $stats['total_users'] ?? 0 }}</p>
                <p class="mt-1 text-xs text-blue-500">Tous les comptes enregistrés</p>
            </div>
            <div class="p-3 bg-blue-200 rounded-lg">
                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="rounded-xl border border-green-200 bg-gradient-to-br from-green-50 to-green-100 p-5 shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-green-600">Praticiens</p>
                <p class="mt-2 text-3xl font-bold text-green-900">{{ $stats['praticiens'] ?? 0 }}</p>
                <p class="mt-1 text-xs text-green-500">Professionnels</p>
            </div>
            <div class="p-3 bg-green-200 rounded-lg">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="rounded-xl border border-purple-200 bg-gradient-to-br from-purple-50 to-purple-100 p-5 shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-purple-600">Patients</p>
                <p class="mt-2 text-3xl font-bold text-purple-900">{{ $stats['patients'] ?? 0 }}</p>
                <p class="mt-1 text-xs text-purple-500">Patients actifs</p>
            </div>
            <div class="p-3 bg-purple-200 rounded-lg">
                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
            </div>
        </div>
    </div>

    <div class="rounded-xl border border-orange-200 bg-gradient-to-br from-orange-50 to-orange-100 p-5 shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-medium uppercase tracking-wider text-orange-600">RDV ce mois</p>
                <p class="mt-2 text-3xl font-bold text-orange-900">{{ $stats['rdv_mois'] ?? 0 }}</p>
                <p class="mt-1 text-xs text-orange-500">Ce mois-ci</p>
            </div>
            <div class="p-3 bg-orange-200 rounded-lg">
                <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <a href="{{ route('admin.users.create') }}" class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-xl shadow-lg p-6 text-white hover:shadow-xl transition transform hover:-translate-y-1 group">
        <div class="text-center">
            <div class="p-3 bg-white/20 rounded-lg mx-auto w-fit mb-3 group-hover:bg-white/30 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold">Nouvel utilisateur</h3>
        </div>
    </a>

    <a href="{{ route('admin.users.index') }}" class="bg-gradient-to-r from-green-600 to-green-800 rounded-xl shadow-lg p-6 text-white hover:shadow-xl transition transform hover:-translate-y-1 group">
        <div class="text-center">
            <div class="p-3 bg-white/20 rounded-lg mx-auto w-fit mb-3 group-hover:bg-white/30 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold">Gérer utilisateurs</h3>
        </div>
    </a>

    <a href="{{ route('admin.services') }}" class="bg-gradient-to-r from-purple-600 to-purple-800 rounded-xl shadow-lg p-6 text-white hover:shadow-xl transition transform hover:-translate-y-1 group">
        <div class="text-center">
            <div class="p-3 bg-white/20 rounded-lg mx-auto w-fit mb-3 group-hover:bg-white/30 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold">Services</h3>
        </div>
    </a>

    <a href="{{ route('admin.rapports') }}" class="bg-gradient-to-r from-orange-600 to-orange-800 rounded-xl shadow-lg p-6 text-white hover:shadow-xl transition transform hover:-translate-y-1 group">
        <div class="text-center">
            <div class="p-3 bg-white/20 rounded-lg mx-auto w-fit mb-3 group-hover:bg-white/30 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            </div>
            <h3 class="text-lg font-bold">Rapports</h3>
        </div>
    </a>
</div>

<!-- Charts -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-md hover:shadow-lg transition-shadow duration-200">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Statistiques des RDV
        </h3>
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
            <canvas id="rdvChart"></canvas>
        </div>
    </div>

    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-md hover:shadow-lg transition-shadow duration-200">
        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Revenus mensuels
        </h3>
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
            <canvas id="caChart"></canvas>
        </div>
    </div>
</div>

<!-- Recent Activity -->
<div class="bg-white rounded-xl border border-gray-200 shadow-md">
    <div class="p-6 border-b flex items-center gap-2">
        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
        </svg>
        <h3 class="text-xl font-bold text-gray-800">Activité récente</h3>
    </div>
    <div class="p-6">
        <div class="space-y-4">
            <div class="flex items-center space-x-4 p-4 bg-blue-50 rounded-lg border-l-4 border-blue-500 hover:shadow-md transition-shadow">
                <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white rounded-full w-10 h-10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-800">Nouvel utilisateur créé</p>
                    <p class="text-xs text-gray-500">Il y a 2 heures</p>
                </div>
            </div>

            <div class="flex items-center space-x-4 p-4 bg-green-50 rounded-lg border-l-4 border-green-500 hover:shadow-md transition-shadow">
                <div class="bg-gradient-to-br from-green-500 to-green-600 text-white rounded-full w-10 h-10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-800">Consultation terminée</p>
                    <p class="text-xs text-gray-500">Il y a 5 heures</p>
                </div>
            </div>

            <div class="flex items-center space-x-4 p-4 bg-purple-50 rounded-lg border-l-4 border-purple-500 hover:shadow-md transition-shadow">
                <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-full w-10 h-10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="text-sm font-semibold text-gray-800">Paiement reçu</p>
                    <p class="text-xs text-gray-500">Il y a 1 jour</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Graphique des rendez-vous (modernisé)
    const rdvCtx = document.getElementById('rdvChart').getContext('2d');
    const rdvGradient = rdvCtx.createLinearGradient(0, 0, 0, 250);
    rdvGradient.addColorStop(0, 'rgba(54, 162, 235, 0.4)');
    rdvGradient.addColorStop(1, 'rgba(54, 162, 235, 0.05)');
    const rdvChart = new Chart(rdvCtx, {
        type: 'line',
        data: {
            labels: @json($rdvParMois->pluck('mois')->map(fn($mois) => \Carbon\Carbon::parse($mois)->format('M Y'))),
            datasets: [{
                label: 'Rendez-vous',
                data: @json($rdvParMois->pluck('total')),
                fill: true,
                backgroundColor: rdvGradient,
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'white',
                pointBorderColor: 'rgba(54, 162, 235, 1)',
                pointRadius: 6,
                pointHoverRadius: 8,
                tension: 0.4
            }]
        },
        options: {
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(54, 162, 235, 0.9)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    padding: 12,
                    borderRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#a0aec0', font: { size: 14 } }
                },
                y: {
                    grid: { color: 'rgba(200,200,200,0.1)' },
                    ticks: { color: '#a0aec0', font: { size: 14 }, beginAtZero: true }
                }
            }
        }
    });

    // Graphique des revenus mensuels (modernisé)
    const caCtx = document.getElementById('caChart').getContext('2d');
    const caGradient = caCtx.createLinearGradient(0, 0, 0, 250);
    caGradient.addColorStop(0, 'rgba(75, 192, 192, 0.4)');
    caGradient.addColorStop(1, 'rgba(75, 192, 192, 0.05)');
    const caChart = new Chart(caCtx, {
        type: 'line',
        data: {
            labels: @json($caParMois->pluck('mois')->map(fn($mois) => \Carbon\Carbon::parse($mois)->format('M Y'))),
            datasets: [{
                label: 'Revenus',
                data: @json($caParMois->pluck('total')),
                fill: true,
                backgroundColor: caGradient,
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                pointBackgroundColor: 'white',
                pointBorderColor: 'rgba(75, 192, 192, 1)',
                pointRadius: 6,
                pointHoverRadius: 8,
                tension: 0.4
            }]
        },
        options: {
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(75, 192, 192, 0.9)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    padding: 12,
                    borderRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#a0aec0', font: { size: 14 } }
                },
                y: {
                    grid: { color: 'rgba(200,200,200,0.1)' },
                    ticks: { color: '#a0aec0', font: { size: 14 }, beginAtZero: true }
                }
            }
        }
    });
</script>
@endsection

