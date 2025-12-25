@extends('layouts.app')

@section('title', 'Centre de rapports')
@section('page-title', 'Rapports et exports')
@section('breadcrumb', 'Rapports')

@section('sidebar')
@include('admin.partials.sidebar')
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header avec gradient professionnel -->
    <div class="rounded-2xl bg-gradient-to-r from-cyan-600 via-cyan-700 to-cyan-900 p-8 text-white shadow-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="rounded-xl bg-white/20 p-3 backdrop-blur-sm">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-3xl font-bold">Centre de rapports</h1>
                    <p class="text-cyan-100 mt-1">Générez des rapports PDF personnalisés</p>
                </div>
            </div>
            <div class="flex gap-2">
                <span class="inline-flex items-center gap-2 rounded-full bg-white/20 px-4 py-1.5 backdrop-blur-sm font-semibold text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    PDF
                </span>
                <span class="inline-flex items-center gap-2 rounded-full bg-white/20 px-4 py-1.5 backdrop-blur-sm font-semibold text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                    Export
                </span>
            </div>
        </div>
    </div>

    <!-- Grille de rapports -->
    <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
        <form method="GET" action="{{ route('admin.rapport.activite') }}" class="space-y-4 rounded-2xl border border-blue-200 bg-gradient-to-br from-blue-50 to-blue-100 p-7 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-start justify-between">
                <div class="flex gap-3">
                    <div class="rounded-lg bg-gradient-to-br from-blue-600 to-blue-800 p-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Rapport d'activité</h2>
                        <p class="text-sm text-gray-600 mt-1">RDV, consultations et patients</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1 rounded-full bg-blue-600 px-3 py-1 text-xs font-bold text-white">
                    <span class="inline-block h-2 w-2 rounded-full bg-white"></span>
                    Actif
                </span>
            </div>
            <p class="text-sm text-gray-700 border-t border-blue-200 pt-4">Statistiques détaillées sur les rendez-vous confirmés, consultations réalisées, nouveaux patients et taux de présence.</p>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 pt-2">
                <div>
                    <label class="text-xs font-bold uppercase text-gray-700 block mb-2">Date début</label>
                    <input type="date" name="date_debut" value="{{ request('date_debut', now()->startOfMonth()->format('Y-m-d')) }}" class="w-full rounded-lg border-2 border-blue-200 px-4 py-2 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-200 transition-all" required>
                </div>
                <div>
                    <label class="text-xs font-bold uppercase text-gray-700 block mb-2">Date fin</label>
                    <input type="date" name="date_fin" value="{{ request('date_fin', now()->endOfMonth()->format('Y-m-d')) }}" class="w-full rounded-lg border-2 border-blue-200 px-4 py-2 text-sm focus:outline-none focus:border-blue-600 focus:ring-2 focus:ring-blue-200 transition-all" required>
                </div>
            </div>
            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-blue-600 to-blue-700 px-4 py-2.5 text-sm font-bold text-white shadow-md hover:shadow-lg hover:from-blue-700 hover:to-blue-800 transition-all duration-300 mt-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
                Générer le PDF
            </button>
        </form>

        <form method="GET" action="{{ route('admin.rapport.financier') }}" class="space-y-4 rounded-2xl border border-emerald-200 bg-gradient-to-br from-emerald-50 to-emerald-100 p-7 shadow-lg hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-start justify-between">
                <div class="flex gap-3">
                    <div class="rounded-lg bg-gradient-to-br from-emerald-600 to-emerald-800 p-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Rapport financier</h2>
                        <p class="text-sm text-gray-600 mt-1">Factures, paiements et encaissements</p>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-600 px-3 py-1 text-xs font-bold text-white">
                    <span class="inline-block h-2 w-2 rounded-full bg-white"></span>
                    Actif
                </span>
            </div>
            <p class="text-sm text-gray-700 border-t border-emerald-200 pt-4">Analyse complète des encaissements, factures émises, paiements par méthode, montants restants et revenue.</p>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2 pt-2">
                <div>
                    <label class="text-xs font-bold uppercase text-gray-700 block mb-2">Date début</label>
                    <input type="date" name="date_debut" value="{{ request('date_debut', now()->startOfMonth()->format('Y-m-d')) }}" class="w-full rounded-lg border-2 border-emerald-200 px-4 py-2 text-sm focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-200 transition-all" required>
                </div>
                <div>
                    <label class="text-xs font-bold uppercase text-gray-700 block mb-2">Date fin</label>
                    <input type="date" name="date_fin" value="{{ request('date_fin', now()->endOfMonth()->format('Y-m-d')) }}" class="w-full rounded-lg border-2 border-emerald-200 px-4 py-2 text-sm focus:outline-none focus:border-emerald-600 focus:ring-2 focus:ring-emerald-200 transition-all" required>
                </div>
            </div>
            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg bg-gradient-to-r from-emerald-600 to-emerald-700 px-4 py-2.5 text-sm font-bold text-white shadow-md hover:shadow-lg hover:from-emerald-700 hover:to-emerald-800 transition-all duration-300 mt-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                </svg>
                Générer le PDF
            </button>
        </form>
    </div>

    <!-- Conseil professionnel -->
    <div class="rounded-2xl border-2 border-dashed border-cyan-300 bg-gradient-to-br from-cyan-50 to-cyan-100/50 p-6 shadow-sm hover:shadow-md transition-all">
        <div class="flex items-start gap-4">
            <div class="rounded-lg bg-cyan-600 p-3 text-white flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="flex-1">
                <p class="font-bold text-gray-900 mb-2">💡 Conseils d'utilisation</p>
                <ul class="text-sm text-gray-700 space-y-1">
                    <li>✓ Les rapports sont générés en PDF et téléchargés automatiquement.</li>
                    <li>✓ Sélectionnez une période pour affiner votre analyse.</li>
                    <li>✓ Pour un suivi temps réel, consultez le <strong>tableau de bord principal</strong>.</li>
                    <li>✓ Exportez directement depuis les modules <strong>Facturation</strong> et <strong>Rendez-vous</strong>.</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
