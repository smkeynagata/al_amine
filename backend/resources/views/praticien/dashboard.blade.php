@extends('praticien.layouts.app')

@section('title', 'Dashboard')

@section('content')
<!-- Welcome Banner with Animation -->
<div class="mb-8 bg-gradient-to-r from-purple-600 via-blue-600 to-cyan-600 rounded-2xl p-8 text-white shadow-2xl relative overflow-hidden animate-fade-in-up">
    <!-- Animated Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 -right-4 w-40 h-40 bg-white rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-0 -left-4 w-40 h-40 bg-white rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
    </div>

    <div class="flex items-center justify-between relative z-10">
        <div class="space-y-3">
            <h1 class="text-4xl font-bold mb-2 animate-slide-in-left">Bienvenue, Dr. {{ auth()->user()->name }} 👋</h1>
            <p class="text-purple-100 text-lg flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                {{ now()->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
            </p>
            <div class="flex items-center gap-4 mt-4">
                <div class="flex items-center gap-2 bg-white bg-opacity-20 backdrop-blur-sm px-4 py-2 rounded-full">
                    <div class="w-2 h-2 bg-green-400 rounded-full animate-ping"></div>
                    <div class="w-2 h-2 bg-green-400 rounded-full absolute"></div>
                    <span class="text-sm font-semibold">Vous êtes en ligne</span>
                </div>
            </div>
        </div>
      
    </div>
</div>

<div class="grid grid-cols-12 gap-6">
    <!-- Main Content - Left Column -->
    <div class="col-span-8 space-y-6">

        <!-- KPI Stats - Enhanced with Animations -->
        <div class="grid grid-cols-3 gap-5">
            <!-- Consultations Aujourd'hui -->
            <div class="group bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-blue-100 hover:border-blue-300 cursor-pointer transform hover:-translate-y-2 animate-fade-in-up">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 text-white p-3 rounded-xl group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-lg">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-blue-600 bg-blue-100 px-3 py-1.5 rounded-full group-hover:bg-blue-200 transition">Aujourd'hui</span>
                </div>
                <div class="space-y-2">
                    <div class="text-5xl font-extrabold bg-gradient-to-r from-blue-600 to-blue-800 bg-clip-text text-transparent">{{ $stats['rdv_aujourdhui'] ?? 0 }}</div>
                    <p class="text-sm font-semibold text-gray-600">Consultations prévues</p>
                    <div class="h-1.5 bg-blue-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full transition-all duration-1000 group-hover:w-full" style="width: 75%"></div>
                    </div>
                </div>
            </div>

            <!-- Patients Ce Mois -->
            <div class="group bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-green-100 hover:border-green-300 cursor-pointer transform hover:-translate-y-2 animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-gradient-to-br from-green-500 to-emerald-600 text-white p-3 rounded-xl group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-lg">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 10a3 3 0 11-6 0 3 3 0 016 0zM12 14a8 8 0 00-8 8v2h16v-2a8 8 0 00-8-8z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-green-600 bg-green-100 px-3 py-1.5 rounded-full group-hover:bg-green-200 transition">Ce mois</span>
                </div>
                <div class="space-y-2">
                    <div class="text-5xl font-extrabold bg-gradient-to-r from-green-600 to-emerald-800 bg-clip-text text-transparent">{{ $stats['consultations_mois'] ?? 0 }}</div>
                    <p class="text-sm font-semibold text-gray-600">Consultations ce mois</p>
                    <div class="h-1.5 bg-green-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-green-500 to-emerald-600 rounded-full transition-all duration-1000 group-hover:w-full" style="width: 60%"></div>
                    </div>
                </div>
            </div>

            <!-- RDV à Venir -->
            <div class="group bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-purple-100 hover:border-purple-300 cursor-pointer transform hover:-translate-y-2 animate-fade-in-up" style="animation-delay: 0.2s;">
                <div class="flex items-center justify-between mb-4">
                    <div class="bg-gradient-to-br from-purple-500 to-purple-600 text-white p-3 rounded-xl group-hover:scale-110 group-hover:rotate-6 transition-all duration-300 shadow-lg">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <span class="text-xs font-bold text-purple-600 bg-purple-100 px-3 py-1.5 rounded-full group-hover:bg-purple-200 transition">7 jours</span>
                </div>
                <div class="space-y-2">
                    <div class="text-5xl font-extrabold bg-gradient-to-r from-purple-600 to-purple-800 bg-clip-text text-transparent">{{ $stats['rdv_a_venir'] ?? 0 }}</div>
                    <p class="text-sm font-semibold text-gray-600">Rendez-vous à venir</p>
                    <div class="h-1.5 bg-purple-100 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-purple-500 to-purple-600 rounded-full transition-all duration-1000 group-hover:w-full" style="width: 85%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rendez-vous du Jour - Enhanced with Timeline Design -->
        <div class="bg-white rounded-2xl p-7 shadow-xl border border-gray-100 animate-fade-in-up" style="animation-delay: 0.3s;">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                        <span class="text-3xl animate-pulse">🕐</span>
                        Rendez-vous Aujourd'hui
                    </h2>
                    <p class="text-sm text-gray-500 mt-2 flex items-center gap-2">
                        <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                        <span class="font-semibold text-gray-700">{{ $rdvAujourdhui->count() }}</span> consultation(s) prévue(s)
                    </p>
                </div>
                <a href="{{ route('praticien.agenda') }}" class="group px-5 py-2.5 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-xl text-sm font-semibold hover:shadow-lg transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Voir agenda complet
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>

            @if($rdvAujourdhui->isNotEmpty())
            <div class="space-y-4">
                @foreach($rdvAujourdhui as $index => $rdv)
                <div class="group relative flex items-center gap-4 p-5 bg-gradient-to-r from-blue-50 via-purple-50 to-pink-50 rounded-xl border-l-4 border-purple-500 hover:shadow-lg hover:scale-[1.02] transition-all duration-300 cursor-pointer animate-slide-in-right" style="animation-delay: {{ $index * 0.1 }}s;">
                    <!-- Timeline dot -->
                    <div class="absolute -left-2 w-4 h-4 bg-purple-500 rounded-full border-4 border-white shadow-lg group-hover:scale-125 transition-transform"></div>

                    <!-- Avatar with gradient -->
                    <div class="relative">
                        <div class="w-14 h-14 bg-gradient-to-br from-purple-500 via-blue-500 to-pink-500 text-white rounded-2xl flex items-center justify-center font-bold text-lg shadow-lg group-hover:rotate-6 transition-transform">
                            {{ substr($rdv->patient->user->prenom, 0, 1) }}{{ substr($rdv->patient->user->name, 0, 1) }}
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-green-500 rounded-full border-2 border-white animate-pulse"></div>
                    </div>

                    <!-- Info -->
                    <div class="flex-1">
                        <p class="font-bold text-gray-900 text-lg mb-1">{{ $rdv->patient->user->nom_complet }}</p>
                        <div class="flex items-center gap-3 text-sm">
                            <span class="flex items-center gap-1 font-bold text-purple-700 bg-purple-100 px-3 py-1 rounded-full">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                {{ $rdv->date_heure_rdv->format('H:i') }}
                            </span>
                            <span class="text-gray-600 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                {{ $rdv->notes ?? 'Consultation générale' }}
                            </span>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-3">
                        <span class="flex items-center gap-2 px-4 py-2 bg-green-100 text-green-800 text-xs font-bold rounded-full border border-green-200">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            Confirmé
                        </span>
                        <a href="{{ route('praticien.consultation.show', $rdv) }}" class="group/btn px-5 py-2.5 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl text-sm font-bold hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                            Consulter
                            <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-12 animate-fade-in">
                <div class="relative inline-block">
                    <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-400 to-blue-400 opacity-20 blur-2xl"></div>
                </div>
                <p class="text-gray-500 font-semibold text-lg mb-2">Aucun rendez-vous prévu aujourd'hui</p>
                <p class="text-gray-400 text-sm">Profitez de cette journée calme pour vous reposer ! 😊</p>
            </div>
            @endif
        </div>

        <!-- Graphique Statistiques - Enhanced -->
        <div class="bg-white rounded-2xl p-7 shadow-xl border border-gray-100 animate-fade-in-up" style="animation-delay: 0.4s;">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-3">
                        <span class="text-3xl">📊</span>
                        Activité Cette Semaine
                    </h2>
                    <p class="text-sm text-gray-500 mt-1">Évolution de vos consultations</p>
                </div>
                <select class="bg-gradient-to-r from-purple-50 to-blue-50 border-2 border-purple-200 rounded-xl px-4 py-2.5 text-sm font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all cursor-pointer hover:shadow-md">
                    <option>Cette semaine</option>
                    <option>Ce mois</option>
                    <option>Cette année</option>
                </select>
            </div>
            <div class="relative bg-gradient-to-br from-purple-50 via-blue-50 to-cyan-50 rounded-xl p-6">
                <canvas id="patientChart" height="70"></canvas>
            </div>

            <!-- Mini Stats under chart -->
            <div class="grid grid-cols-3 gap-4 mt-6">
                <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['rdv_aujourdhui'] ?? 0 }}</div>
                    <div class="text-xs text-gray-600 mt-1">Aujourd'hui</div>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl">
                    <div class="text-2xl font-bold text-purple-600">{{ $stats['rdv_a_venir'] ?? 0 }}</div>
                    <div class="text-xs text-gray-600 mt-1">À venir</div>
                </div>
                <div class="text-center p-4 bg-gradient-to-br from-green-50 to-green-100 rounded-xl">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['consultations_mois'] ?? 0 }}</div>
                    <div class="text-xs text-gray-600 mt-1">Ce mois</div>
                </div>
            </div>
        </div>

        <!-- Performance Indicators - New Section -->
        <div class="bg-white rounded-2xl p-7 shadow-xl border border-gray-100 animate-fade-in-up" style="animation-delay: 0.45s;">
            <h2 class="text-2xl font-bold text-gray-900 flex items-center gap-3 mb-6">
                <span class="text-3xl">🎯</span>
                Indicateurs de Performance
            </h2>

            <div class="grid grid-cols-2 gap-4">
                <!-- Taux de Confirmation -->
                @php
                    $totalRdv = $stats['rdv_aujourdhui'] + $stats['rdv_a_venir'];
                    $tauxConfirmation = $totalRdv > 0 ? round(($stats['rdv_a_venir'] / $totalRdv) * 100) : 0;
                @endphp
                <div class="relative group p-5 bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl border border-blue-200 hover:shadow-lg transition-all duration-300 cursor-pointer">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-bold text-blue-700">Taux Confirmation</span>
                        <div class="w-10 h-10 bg-blue-500 text-white rounded-lg flex items-center justify-center font-bold text-lg">✓</div>
                    </div>
                    <div class="text-3xl font-bold text-blue-900">{{ $tauxConfirmation }}%</div>
                    <div class="mt-3 h-2 bg-blue-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full" style="width: {{ $tauxConfirmation }}%"></div>
                    </div>
                </div>

                <!-- RDV Aujourd'hui -->
                <div class="relative group p-5 bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl border border-purple-200 hover:shadow-lg transition-all duration-300 cursor-pointer">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-bold text-purple-700">RDV Aujourd'hui</span>
                        <div class="w-10 h-10 bg-purple-500 text-white rounded-lg flex items-center justify-center font-bold text-lg">📅</div>
                    </div>
                    <div class="text-3xl font-bold text-purple-900">{{ $stats['rdv_aujourdhui'] }}</div>
                    <div class="mt-3 h-2 bg-purple-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-purple-500 to-purple-600 rounded-full" style="width: {{ min(100, ($stats['rdv_aujourdhui'] / 10) * 100) }}%"></div>
                    </div>
                </div>

                <!-- Consultations Mois -->
                <div class="relative group p-5 bg-gradient-to-br from-green-50 to-green-100 rounded-xl border border-green-200 hover:shadow-lg transition-all duration-300 cursor-pointer">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-bold text-green-700">Consultations Mois</span>
                        <div class="w-10 h-10 bg-green-500 text-white rounded-lg flex items-center justify-center font-bold text-lg">✓</div>
                    </div>
                    <div class="text-3xl font-bold text-green-900">{{ $stats['consultations_mois'] }}</div>
                    <div class="mt-3 h-2 bg-green-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-green-500 to-green-600 rounded-full" style="width: {{ min(100, ($stats['consultations_mois'] / 50) * 100) }}%"></div>
                    </div>
                </div>

                <!-- Demandes en attente -->
                <div class="relative group p-5 bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl border border-orange-200 hover:shadow-lg transition-all duration-300 cursor-pointer">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-bold text-orange-700">Demandes RDV</span>
                        <div class="w-10 h-10 bg-orange-500 text-white rounded-lg flex items-center justify-center font-bold text-lg">�</div>
                    </div>
                    <div class="text-3xl font-bold text-orange-900">{{ $demandesRdv->count() }}<span class="text-sm"> en attente</span></div>
                    <div class="mt-3 h-2 bg-orange-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-orange-500 to-orange-600 rounded-full" style="width: {{ min(100, ($demandesRdv->count() / 10) * 100) }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar - Right Column -->
    <div class="col-span-4 space-y-6">

        <!-- Quick Actions Card - Enhanced -->
        <div class="bg-gradient-to-br from-purple-600 via-blue-600 to-cyan-600 rounded-2xl p-6 text-white shadow-2xl relative overflow-hidden animate-fade-in-up" style="animation-delay: 0.5s;">
            <!-- Animated Background -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white rounded-full blur-2xl animate-pulse"></div>
                <div class="absolute bottom-0 left-0 w-32 h-32 bg-white rounded-full blur-2xl animate-pulse" style="animation-delay: 1s;"></div>
            </div>

            <div class="relative z-10">
                <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <span class="text-2xl">⚡</span>
                    Actions Rapides
                </h3>
                <div class="space-y-3">
                    <a href="{{ route('praticien.agenda') }}" class="group block w-full bg-white bg-opacity-20 hover:bg-opacity-30 backdrop-blur-sm text-white px-5 py-3.5 rounded-xl font-semibold transition-all text-center transform hover:scale-105 hover:shadow-lg flex items-center justify-center gap-2">
                        <span class="text-xl">📅</span>
                        <span>Voir l'agenda complet</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="{{ route('praticien.patients') }}" class="group block w-full bg-white bg-opacity-20 hover:bg-opacity-30 backdrop-blur-sm text-white px-5 py-3.5 rounded-xl font-semibold transition-all text-center transform hover:scale-105 hover:shadow-lg flex items-center justify-center gap-2">
                        <span class="text-xl">👥</span>
                        <span>Mes patients</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="{{ route('praticien.consultations') }}" class="group block w-full bg-white bg-opacity-20 hover:bg-opacity-30 backdrop-blur-sm text-white px-5 py-3.5 rounded-xl font-semibold transition-all text-center transform hover:scale-105 hover:shadow-lg flex items-center justify-center gap-2">
                        <span class="text-xl">📋</span>
                        <span>Consultations</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                    <a href="{{ route('praticien.disponibilites') }}" class="group block w-full bg-white bg-opacity-20 hover:bg-opacity-30 backdrop-blur-sm text-white px-5 py-3.5 rounded-xl font-semibold transition-all text-center transform hover:scale-105 hover:shadow-lg flex items-center justify-center gap-2">
                        <span class="text-xl">⏰</span>
                        <span>Disponibilités</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Prochains RDV Card - Enhanced -->
        <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100 animate-fade-in-up" style="animation-delay: 0.6s;">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span class="text-2xl">📌</span>
                Prochains RDV
            </h3>

            @if($prochainsRdv->isNotEmpty())
            <div class="space-y-3">
                @foreach($prochainsRdv->take(5) as $index => $rdv)
                <div class="group p-4 bg-gradient-to-r from-blue-50 via-purple-50 to-pink-50 rounded-xl border-l-4 border-blue-500 hover:shadow-lg hover:scale-[1.02] transition-all duration-300 cursor-pointer animate-slide-in-right" style="animation-delay: {{ 0.7 + ($index * 0.05) }}s;">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-500 text-white rounded-xl flex items-center justify-center font-bold text-sm shadow group-hover:rotate-6 transition-transform">
                            {{ substr($rdv->patient->user->prenom, 0, 1) }}{{ substr($rdv->patient->user->name, 0, 1) }}
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-sm text-gray-900">{{ $rdv->patient->user->nom_complet }}</p>
                            <p class="text-xs text-gray-600 mt-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-bold text-purple-600">{{ $rdv->date_heure_rdv->format('d/m H:i') }}</span>
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <div class="text-5xl mb-3">📭</div>
                <p class="text-gray-500 text-sm">Aucun rendez-vous prévu</p>
            </div>
            @endif
        </div>

        <!-- Profile Card - Enhanced -->
        <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100 animate-fade-in-up" style="animation-delay: 0.7s;">
            <h3 class="text-xl font-bold text-gray-900 mb-5 flex items-center gap-2">
                <span class="text-2xl">👤</span>
                Mon Profil
            </h3>
            <div class="text-center">
                <div class="relative inline-block mb-4">
                    <div class="w-20 h-20 bg-gradient-to-br from-purple-500 via-blue-500 to-pink-500 text-white rounded-2xl flex items-center justify-center font-bold text-3xl mx-auto shadow-2xl transform hover:rotate-6 transition-transform">
                        {{ substr(auth()->user()->prenom, 0, 1) }}{{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <div class="absolute -bottom-1 -right-1 w-6 h-6 bg-green-500 rounded-full border-4 border-white animate-pulse"></div>
                </div>
                <h4 class="font-bold text-gray-900 text-lg">Dr. {{ auth()->user()->nom_complet }}</h4>
                <p class="text-sm text-gray-600 mt-2 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    {{ auth()->user()->email }}
                </p>
                <a href="{{ route('praticien.profile') }}" class="group inline-flex items-center gap-2 mt-5 px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-xl text-sm font-semibold hover:shadow-xl transform hover:scale-105 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                    Voir le profil complet
                    <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Statistics Summary - Enhanced -->
        <div class="bg-gradient-to-br from-purple-50 via-blue-50 to-cyan-50 rounded-2xl p-6 shadow-xl border border-purple-100 animate-fade-in-up" style="animation-delay: 0.8s;">
            <h3 class="text-xl font-bold text-gray-900 mb-5 flex items-center gap-2">
                <span class="text-2xl">📈</span>
                Résumé Statistiques
            </h3>
            <div class="space-y-5">
                <div class="bg-white rounded-xl p-4 shadow-md hover:shadow-lg transition-all duration-300 group cursor-pointer">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-semibold text-gray-600">Total consultations</span>
                        <span class="font-bold text-2xl bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent group-hover:scale-110 transition-transform">{{ $stats['consultations_mois'] }}</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-purple-500 via-blue-500 to-cyan-500 rounded-full animate-progress" style="width: {{ min(100, ($stats['consultations_mois'] / 50) * 100) }}%"></div>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 shadow-md hover:shadow-lg transition-all duration-300 group cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-sm font-semibold text-gray-600">Patients uniques</span>
                        </div>
                        <span class="font-bold text-xl text-green-600 group-hover:scale-110 transition-transform">{{ $stats['patients_total'] }}</span>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 shadow-md hover:shadow-lg transition-all duration-300 group cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-blue-500 rounded-full animate-pulse" style="animation-delay: 0.5s;"></div>
                            <span class="text-sm font-semibold text-gray-600">Taux de remplissage</span>
                        </div>
                        <span class="font-bold text-xl text-blue-600 group-hover:scale-110 transition-transform">{{ $stats['rdv_a_venir'] > 0 ? round(($stats['rdv_aujourdhui'] / ($stats['rdv_a_venir'] + $stats['rdv_aujourdhui'])) * 100) : 0 }}%</span>
                    </div>
                </div>

                <div class="bg-white rounded-xl p-4 shadow-md hover:shadow-lg transition-all duration-300 group cursor-pointer">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <div class="w-3 h-3 bg-orange-500 rounded-full animate-pulse" style="animation-delay: 1s;"></div>
                            <span class="text-sm font-semibold text-gray-600">Demandes en attente</span>
                        </div>
                        <span class="font-bold text-xl text-orange-600 group-hover:scale-110 transition-transform">{{ $demandesRdv->count() }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Demandes de RDV - Real Data -->
        <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100 animate-fade-in-up" style="animation-delay: 0.85s;">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                    <span class="text-2xl">�</span>
                    Demandes de RDV
                </h3>
                @if($demandesRdv->count() > 0)
                <span class="bg-orange-100 text-orange-800 text-xs font-bold px-3 py-1 rounded-full">
                    {{ $demandesRdv->count() }}
                </span>
                @endif
            </div>

            @if($demandesRdv->isNotEmpty())
            <div class="space-y-3 max-h-96 overflow-y-auto custom-scrollbar">
                @foreach($demandesRdv as $demande)
                <div class="relative flex gap-3 p-3 bg-gradient-to-r from-orange-50 to-orange-100 rounded-lg border-l-4 border-orange-500 hover:shadow-md transition-all">
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-red-500 text-white rounded-xl flex items-center justify-center font-bold text-sm">
                            {{ substr($demande->patient->user->prenom, 0, 1) }}{{ substr($demande->patient->user->name, 0, 1) }}
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900">{{ $demande->patient->user->nom_complet }}</p>
                        <p class="text-xs text-gray-600 mt-0.5">{{ $demande->date_heure_souhaitee->format('d/m/Y à H:i') }}</p>
                        <p class="text-xs text-gray-500 mt-1">{{ Str::limit($demande->motif, 40) }}</p>
                    </div>
                    <div class="flex items-center">
                        <span class="text-xs text-orange-600 font-semibold">{{ $demande->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <div class="text-5xl mb-3">📭</div>
                <p class="text-gray-500 text-sm">Aucune demande en attente</p>
            </div>
            @endif
        </div>

        <!-- Recent Activities - Real Data -->
        <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100 animate-fade-in-up" style="animation-delay: 0.9s;">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span class="text-2xl">🔔</span>
                Activités Récentes
            </h3>

            @if($notifications->isNotEmpty())
            <div class="space-y-3 max-h-96 overflow-y-auto custom-scrollbar">
                @foreach($notifications as $notification)
                @php
                    $colorMap = [
                        'blue' => ['from-blue-50', 'to-blue-100', 'border-blue-500', 'bg-blue-500'],
                        'green' => ['from-green-50', 'to-green-100', 'border-green-500', 'bg-green-500'],
                        'purple' => ['from-purple-50', 'to-purple-100', 'border-purple-500', 'bg-purple-500'],
                        'orange' => ['from-orange-50', 'to-orange-100', 'border-orange-500', 'bg-orange-500'],
                        'red' => ['from-red-50', 'to-red-100', 'border-red-500', 'bg-red-500'],
                    ];
                    $colors = $colorMap[$notification['color']] ?? $colorMap['blue'];
                @endphp
                <div class="relative flex gap-3 p-3 bg-gradient-to-r {{ $colors[0] }} {{ $colors[1] }} rounded-lg border-l-4 {{ $colors[2] }} hover:shadow-md transition-all">
                    <div class="flex-shrink-0">
                        <div class="flex items-center justify-center h-8 w-8 rounded-lg {{ $colors[3] }} text-white">
                            <span class="text-lg">{{ $notification['icon'] }}</span>
                        </div>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900">{{ $notification['title'] }}</p>
                        <p class="text-xs text-gray-600 mt-0.5">{{ $notification['description'] }} - {{ $notification['time'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <div class="text-5xl mb-3">🔕</div>
                <p class="text-gray-500 text-sm">Aucune activité récente</p>
            </div>
            @endif
        </div>

        <!-- Patients Récents - Real Data -->
        <div class="bg-white rounded-2xl p-6 shadow-xl border border-gray-100 animate-fade-in-up" style="animation-delay: 0.95s;">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span class="text-2xl">👥</span>
                Patients Récents
            </h3>

            @if($patientsRecents->isNotEmpty())
            <div class="space-y-3">
                @foreach($patientsRecents as $patient)
                <div class="flex items-center gap-3 p-3 bg-gradient-to-r from-blue-50 to-purple-50 rounded-lg hover:shadow-md transition-all">
                    <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-purple-500 text-white rounded-xl flex items-center justify-center font-bold text-sm">
                        {{ substr($patient->user->prenom, 0, 1) }}{{ substr($patient->user->name, 0, 1) }}
                    </div>
                    <div class="flex-1">
                        <p class="text-sm font-semibold text-gray-900">{{ $patient->user->nom_complet }}</p>
                        <p class="text-xs text-gray-600">{{ $patient->consultations_count }} consultation(s)</p>
                    </div>
                    <a href="{{ route('praticien.patients') }}" class="text-xs text-blue-600 font-semibold hover:text-blue-800">
                        Voir →
                    </a>
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-8">
                <div class="text-5xl mb-3">👤</div>
                <p class="text-gray-500 text-sm">Aucun patient récent</p>
            </div>
            @endif
        </div>

        <!-- Quick Tips Card -->
        <div class="bg-gradient-to-br from-amber-50 to-orange-50 rounded-2xl p-6 shadow-xl border border-amber-200 animate-fade-in-up" style="animation-delay: 1.0s;">
            <h3 class="text-xl font-bold text-gray-900 mb-4 flex items-center gap-2">
                <span class="text-2xl">💡</span>
                Conseil du Jour
            </h3>

            <div class="space-y-3">
                <div class="p-4 bg-white rounded-lg border-l-4 border-amber-500 shadow-sm">
                    <p class="text-sm font-semibold text-gray-900 mb-2">✨ Astuce pour gagner du temps</p>
                    <p class="text-xs text-gray-600">Utilisez les templates de consultation pour accélérer la saisie des données courantes.</p>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-xs text-gray-600 font-semibold">Prochaine mise à jour système</span>
                    <span class="text-xs font-bold text-amber-600 bg-amber-100 px-3 py-1 rounded-full">Demain</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    // Enhanced Chart with animations and gradients
    const ctx = document.getElementById('patientChart');

    // Create gradient
    const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 400);
    gradient.addColorStop(0, 'rgba(139, 92, 246, 0.4)');
    gradient.addColorStop(0.5, 'rgba(59, 130, 246, 0.2)');
    gradient.addColorStop(1, 'rgba(6, 182, 212, 0.1)');

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'],
            datasets: [{
                label: 'Consultations',
                data: [20, 35, 28, 42, 38, 45, 35],
                borderColor: '#8b5cf6',
                backgroundColor: gradient,
                tension: 0.4,
                fill: true,
                pointRadius: 6,
                pointHoverRadius: 8,
                pointBackgroundColor: '#8b5cf6',
                pointBorderColor: '#fff',
                pointBorderWidth: 3,
                pointHoverBackgroundColor: '#fff',
                pointHoverBorderColor: '#8b5cf6',
                pointHoverBorderWidth: 3,
                borderWidth: 3,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            animation: {
                duration: 2000,
                easing: 'easeInOutQuart'
            },
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    enabled: true,
                    backgroundColor: 'rgba(139, 92, 246, 0.95)',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    padding: 12,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y + ' consultations';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(139, 92, 246, 0.1)',
                        drawBorder: false
                    },
                    ticks: {
                        color: '#6b7280',
                        font: {
                            size: 12,
                            weight: '600'
                        },
                        padding: 10
                    }
                },
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#6b7280',
                        font: {
                            size: 12,
                            weight: '600'
                        },
                        padding: 10
                    }
                }
            }
        }
    });

    // Add smooth scroll animation for page load
    document.addEventListener('DOMContentLoaded', function() {
        const animatedElements = document.querySelectorAll('.animate-fade-in-up, .animate-slide-in-left, .animate-slide-in-right');

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, { threshold: 0.1 });

        animatedElements.forEach(el => observer.observe(el));
    });

    // Add hover effects for performance cards
    document.querySelectorAll('[class*="bg-gradient-to-br"]').forEach(card => {
        if (card.classList.contains('border')) {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.03) translateY(-5px)';
                this.style.transition = 'all 0.3s cubic-bezier(0.4, 0, 0.2, 1)';
            });
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1) translateY(0)';
            });
        }
    });
</script>

<style>
    /* Custom animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes slideInLeft {
        from {
            opacity: 0;
            transform: translateX(-30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    @keyframes bounceSlow {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-20px);
        }
    }

    @keyframes progress {
        from {
            width: 0;
        }
    }

    @keyframes shimmer {
        0% {
            background-position: -1000px 0;
        }
        100% {
            background-position: 1000px 0;
        }
    }

    @keyframes glow {
        0%, 100% {
            box-shadow: 0 0 20px rgba(139, 92, 246, 0.3);
        }
        50% {
            box-shadow: 0 0 30px rgba(139, 92, 246, 0.6);
        }
    }

    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }

    .animate-slide-in-left {
        animation: slideInLeft 0.6s ease-out forwards;
    }

    .animate-slide-in-right {
        animation: slideInRight 0.6s ease-out forwards;
    }

    .animate-bounce-slow {
        animation: bounceSlow 3s ease-in-out infinite;
    }

    .animate-progress {
        animation: progress 1.5s ease-out forwards;
    }

    .animate-fade-in {
        animation: fadeInUp 0.8s ease-out forwards;
    }

    /* Smooth transitions */
    * {
        transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Custom scrollbar for activities section */
    .custom-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: rgba(139, 92, 246, 0.5) transparent;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #8b5cf6, #3b82f6);
        border-radius: 3px;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #7c3aed, #2563eb);
    }

    /* Global scrollbar styling */
    ::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb {
        background: linear-gradient(to bottom, #8b5cf6, #3b82f6);
        border-radius: 10px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: linear-gradient(to bottom, #7c3aed, #2563eb);
    }

    /* Card hover effects */
    .group:hover {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Glow effect for active elements */
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }

    /* Enhanced button hover effects */
    a:not([class*="text-"]) {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* Card shadow elevation */
    .shadow-xl {
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    }

    .shadow-2xl {
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.15), 0 12px 12px -8px rgba(0, 0, 0, 0.05);
    }

    /* Backdrop blur effect */
    .backdrop-blur-sm {
        backdrop-filter: blur(4px);
        -webkit-backdrop-filter: blur(4px);
    }

    /* Gradient text animation */
    @keyframes gradientText {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }

    /* Responsive adjustments */
    @media (max-width: 1024px) {
        .col-span-4 {
            @apply col-span-12;
        }

        .col-span-8 {
            @apply col-span-12;
        }

        .grid-cols-3 {
            @apply grid-cols-1;
        }
    }
</style>
@endsection

