@extends('layouts.app')

@section('title', 'Dashboard Patient')
@section('page-title', 'Tableau de bord')
@section('breadcrumb', 'Accueil')

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('sidebar')
    @include('patient.partials.sidebar')
@endsection

@section('content')
<div class="space-y-8">
    <div class="rounded-3xl bg-gradient-to-r from-blue-600 via-indigo-600 to-purple-600 px-8 py-10 text-white shadow-xl">
        <div class="flex flex-col gap-6 md:flex-row md:items-center md:justify-between">
            <div>
                <h2 class="text-3xl font-bold">Bonjour {{ auth()->user()->prenom }} 👋</h2>
                <p class="mt-2 text-sm text-blue-100">Retrouvez vos prochains rendez-vous et vos suivis administratifs en un coup d'œil.</p>
            </div>
            <a href="{{ route('patient.demander-rdv') }}" class="inline-flex items-center gap-2 rounded-xl bg-white/15 px-5 py-3 text-sm font-semibold text-white transition hover:bg-white/25">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Demander un rendez-vous
            </a>
        </div>
    </div>

    @if(!auth()->user()->patient->poids && !auth()->user()->patient->taille && !auth()->user()->patient->groupe_sanguin)
        <div class="rounded-2xl border border-blue-200 bg-blue-50 px-6 py-5 shadow-sm">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0">
                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <h3 class="text-sm font-semibold text-blue-900">Complétez votre profil de santé</h3>
                    <p class="mt-1 text-sm text-blue-700">Pour un meilleur suivi médical, pensez à renseigner vos informations de santé (poids, taille, groupe sanguin, maladies chroniques, etc.)</p>
                    <a href="{{ route('patient.profile.edit') }}" class="mt-3 inline-flex items-center gap-2 text-sm font-semibold text-blue-600 hover:text-blue-700">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Compléter mon profil
                    </a>
                </div>
            </div>
        </div>
    @endif

    @if(session('success'))
        <div class="rounded-2xl border border-green-200 bg-green-50 px-5 py-4 text-sm text-green-800 shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-4">
        <div class="rounded-2xl border border-blue-100 bg-white p-6 shadow-sm">
            <p class="text-sm font-semibold text-blue-600">Rendez-vous à venir</p>
            <p class="mt-3 text-3xl font-bold text-gray-900">{{ $stats['rdv_a_venir'] ?? 0 }}</p>
            <p class="mt-1 text-xs text-gray-500">Confirmés et programmés</p>
        </div>
        <div class="rounded-2xl border border-emerald-100 bg-white p-6 shadow-sm">
            <p class="text-sm font-semibold text-emerald-600">Consultations réalisées</p>
            <p class="mt-3 text-3xl font-bold text-gray-900">{{ $stats['consultations_total'] ?? 0 }}</p>
            <p class="mt-1 text-xs text-gray-500">Depuis votre inscription</p>
        </div>
        <div class="rounded-2xl border border-yellow-100 bg-white p-6 shadow-sm">
            <p class="text-sm font-semibold text-yellow-600">Factures en attente</p>
            <p class="mt-3 text-3xl font-bold text-gray-900">{{ $stats['factures_impayees'] ?? 0 }}</p>
            <p class="mt-1 text-xs text-gray-500">À régler dès que possible</p>
        </div>
        <div class="rounded-2xl border border-rose-100 bg-white p-6 shadow-sm">
            <p class="text-sm font-semibold text-rose-600">Montant dû</p>
            <p class="mt-3 text-3xl font-bold text-gray-900">{{ number_format($stats['montant_du'] ?? 0, 0, ',', ' ') }} FCFA</p>
            <p class="mt-1 text-xs text-gray-500">Total des factures non soldées</p>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Vos prochains rendez-vous</h3>
                        <p class="text-xs text-gray-500">Les 5 prochains créneaux programmés</p>
                    </div>
                    <a href="{{ route('patient.mes-rdv') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Tout voir →</a>
                </div>

                @forelse($prochainsRdv as $rdv)
                    <div class="mt-4 flex flex-col gap-4 rounded-2xl border border-blue-50 bg-blue-50/50 p-4 shadow-sm">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-xs uppercase text-blue-500">{{ $rdv->date_heure_rdv?->locale('fr')->isoFormat('dddd D MMMM YYYY') }}</p>
                                <h4 class="text-lg font-semibold text-gray-900">Dr. {{ $rdv->praticien->user->nom_complet }}</h4>
                                <p class="text-xs text-gray-500">
                                    {{ $rdv->praticien->specialites->pluck('nom')->implode(', ') ?: 'Spécialité non renseignée' }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2 text-sm">
                                <span class="inline-flex items-center gap-2 rounded-full bg-blue-100 px-3 py-1 text-blue-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3" />
                                    </svg>
                                    {{ $rdv->date_heure_rdv?->format('H:i') }}
                                </span>
                                <span class="inline-flex items-center gap-2 rounded-full bg-white px-3 py-1 text-gray-600">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" />
                                    </svg>
                                    {{ $rdv->duree_minutes ?: 30 }} min
                                </span>
                            </div>
                        </div>
                        @if(filled($rdv->notes))
                            <p class="rounded-xl bg-white/70 px-4 py-3 text-xs text-gray-600">{{ $rdv->notes }}</p>
                        @endif
                        <div class="flex items-center justify-between text-xs text-gray-500">
                            <span>{{ $rdv->date_heure_rdv?->diffForHumans() }}</span>
                            <span class="inline-flex items-center gap-1 rounded-full bg-white px-3 py-1 font-semibold text-blue-600">
                                {{ $rdv->statut_display }}
                            </span>
                        </div>
                    </div>
                @empty
                    <div class="mt-4 rounded-2xl border border-dashed border-blue-200 bg-white p-8 text-center text-sm text-gray-500">
                        Aucun rendez-vous confirmé pour le moment. <a href="{{ route('patient.demander-rdv') }}" class="font-semibold text-blue-600">Prenez rendez-vous</a> pour consulter un praticien.
                    </div>
                @endforelse
            </div>

            <!-- Graphiques suivi santé -->
            @if(!empty($chartData['dates']))
            <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Suivi Santé</h3>
                        <p class="text-xs text-gray-500">Évolution des 3 derniers mois</p>
                    </div>
                    <a href="{{ route('patient.suivi-sante.index') }}" class="text-sm font-semibold text-blue-600 hover:text-blue-700">Voir détails →</a>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Évolution du poids</h4>
                        <canvas id="poidsChartDashboard" height="200"></canvas>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-gray-700 mb-3">Tension artérielle</h4>
                        <canvas id="tensionChartDashboard" height="200"></canvas>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900">Actions rapides</h3>
                <div class="mt-4 space-y-3 text-sm">
                    <a href="{{ route('patient.mes-demandes') }}" class="flex items-center justify-between rounded-xl border border-gray-200 px-4 py-3 hover:border-blue-300 hover:bg-blue-50">
                        <span>Suivre mes demandes</span>
                        <span class="text-blue-500">→</span>
                    </a>
                    <a href="{{ route('patient.mes-rdv') }}" class="flex items-center justify-between rounded-xl border border-gray-200 px-4 py-3 hover:border-blue-300 hover:bg-blue-50">
                        <span>Gérer mes rendez-vous</span>
                        <span class="text-blue-500">→</span>
                    </a>
                    <a href="{{ route('patient.factures') }}" class="flex items-center justify-between rounded-xl border border-gray-200 px-4 py-3 hover:border-blue-300 hover:bg-blue-50">
                        <span>Consulter mes factures</span>
                        <span class="text-blue-500">→</span>
                    </a>
                </div>
            </div>

            <div class="rounded-3xl border border-blue-100 bg-blue-50 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-blue-900">Besoin d'aide ?</h3>
                <p class="mt-2 text-sm text-blue-800">Contactez la secrétaire pour toute modification ou annulation de rendez-vous.</p>
                <a href="tel:{{ config('app.support_phone', 'N/A') }}" class="mt-4 inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-blue-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    Appeler le secrétariat
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@if(!empty($chartData['dates']))
<script>
    const chartData = @json($chartData);
    
    // Graphique Poids Dashboard
    new Chart(document.getElementById('poidsChartDashboard'), {
        type: 'line',
        data: {
            labels: chartData.dates,
            datasets: [{
                label: 'Poids (kg)',
                data: chartData.poids,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {display: false}
            },
            scales: {
                y: {
                    beginAtZero: false
                }
            }
        }
    });
    
    // Graphique Tension Dashboard
    new Chart(document.getElementById('tensionChartDashboard'), {
        type: 'line',
        data: {
            labels: chartData.dates,
            datasets: [{
                label: 'Systolique',
                data: chartData.tension_systolique,
                borderColor: 'rgb(239, 68, 68)',
                tension: 0.4
            }, {
                label: 'Diastolique',
                data: chartData.tension_diastolique,
                borderColor: 'rgb(59, 130, 246)',
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: false
                }
            }
        }
    });
</script>
@endif
@endpush
