@extends('layouts.app')

@section('title', 'Gestion des utilisateurs')
@section('page-title', 'Gestion des utilisateurs')
@section('breadcrumb', 'Utilisateurs')

@section('sidebar')
@include('admin.partials.sidebar')
@endsection

@section('content')
<div class="space-y-8">
<!-- Header & Actions -->
<div class="relative mb-8 overflow-hidden rounded-2xl bg-gradient-to-r from-blue-600 via-blue-700 to-blue-900 p-8 shadow-lg">
    <div class="absolute inset-0 opacity-0"></div>
    <div class="relative z-10 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <div class="flex items-center gap-3 mb-3">
                <div class="p-3 bg-white/20 rounded-lg backdrop-blur-sm">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-2a6 6 0 0112 0v2zm0 0h6v-2a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white">Gestion des utilisateurs</h1>
            </div>
            <p class="text-blue-100">Visualisez, filtrez et gérez l'ensemble des utilisateurs de la plateforme.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-white text-blue-600 px-4 py-2 text-sm font-medium shadow-lg hover:shadow-xl transition-transform duration-200 hover:scale-105">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>Ajouter un utilisateur</span>
        </a>
    </div>
</div>    <!-- Metrics -->
    <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
        <div class="rounded-xl border border-gray-200 bg-gradient-to-br from-blue-50 to-blue-100 p-5 shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-blue-600">Total</p>
                    <p class="mt-2 text-3xl font-bold text-blue-900">{{ $metrics['total'] ?? 0 }}</p>
                    <p class="mt-1 text-xs text-blue-500">Tous les comptes enregistrés</p>
                </div>
                <div class="p-3 bg-blue-200 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.856-1.487M15 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="rounded-xl border border-emerald-200 bg-gradient-to-br from-emerald-50 to-emerald-100 p-5 shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-emerald-600">Actifs</p>
                    <p class="mt-2 text-3xl font-bold text-emerald-700">{{ $metrics['actifs'] ?? 0 }}</p>
                    <p class="mt-1 text-xs text-emerald-500">Comptes en activité</p>
                </div>
                <div class="p-3 bg-emerald-200 rounded-lg">
                    <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="rounded-xl border border-indigo-200 bg-gradient-to-br from-indigo-50 to-indigo-100 p-5 shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-indigo-600">Administrateurs</p>
                    <p class="mt-2 text-3xl font-bold text-indigo-700">{{ $metrics['admins'] ?? 0 }}</p>
                    <p class="mt-1 text-xs text-indigo-500">Gestion des accès</p>
                </div>
                <div class="p-3 bg-indigo-200 rounded-lg">
                    <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>
        <div class="rounded-xl border border-orange-200 bg-gradient-to-br from-orange-50 to-orange-100 p-5 shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-1">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wider text-orange-600">Nouveaux (30 j)</p>
                    <p class="mt-2 text-3xl font-bold text-orange-700">{{ $metrics['recents'] ?? 0 }}</p>
                    <p class="mt-1 text-xs text-orange-500">Inscrits ce mois-ci</p>
                </div>
                <div class="p-3 bg-orange-200 rounded-lg">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-md hover:shadow-lg transition-shadow duration-200">
        <div class="mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
            </svg>
            <h3 class="text-sm font-semibold text-gray-700">Filtres</h3>
        </div>
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-5">
            <div class="md:col-span-2">
                <label class="text-xs font-medium uppercase text-gray-500">Recherche</label>
                <div class="relative mt-1">
                    <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom, email, téléphone..." class="pl-10 w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm focus:border-blue-500 focus:bg-white focus:outline-none" />
                </div>
            </div>
            <div>
                <label class="text-xs font-medium uppercase text-gray-500">Rôle</label>
                <select name="role" class="mt-1 w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm focus:border-blue-500 focus:bg-white focus:outline-none">
                    <option value="">Tous</option>
                    <option value="PATIENT" @selected(request('role') === 'PATIENT')>Patient</option>
                    <option value="PRATICIEN" @selected(request('role') === 'PRATICIEN')>Praticien</option>
                    <option value="SECRETAIRE" @selected(request('role') === 'SECRETAIRE')>Secrétaire</option>
                    <option value="ADMIN" @selected(request('role') === 'ADMIN')>Administrateur</option>
                </select>
            </div>
            <div>
                <label class="text-xs font-medium uppercase text-gray-500">Statut</label>
                <select name="statut" class="mt-1 w-full rounded-lg border border-gray-200 bg-gray-50 px-3 py-2 text-sm focus:border-blue-500 focus:bg-white focus:outline-none">
                    <option value="">Tous</option>
                    <option value="ACTIF" @selected(request('statut') === 'ACTIF')>Actif</option>
                    <option value="SUSPENDU" @selected(request('statut') === 'SUSPENDU')>Suspendu</option>
                    <option value="DESACTIVE" @selected(request('statut') === 'DESACTIVE')>Désactivé</option>
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 rounded-lg bg-gradient-to-r from-blue-600 to-blue-800 px-4 py-2 text-sm font-medium text-white shadow-lg hover:shadow-xl transition-all duration-200 hover:scale-105">
                    <svg class="inline w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                    </svg>
                    Filtrer
                </button>
                <a href="{{ route('admin.users.index') }}" class="rounded-lg border border-gray-300 px-3 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100 transition-colors duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </a>
            </div>
        </form>
    </div>

    <!-- Tableau -->
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-md hover:shadow-lg transition-shadow duration-200">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gradient-to-r from-gray-50 to-gray-100 text-xs uppercase tracking-wider text-gray-600 border-b border-gray-200">
                    <tr>
                        <th class="px-4 py-3 text-left">Utilisateur</th>
                        <th class="px-4 py-3 text-left">Contact</th>
                        <th class="px-4 py-3 text-left">Rôle</th>
                        <th class="px-4 py-3 text-left">Statut</th>
                        <th class="px-4 py-3 text-left">Créé le</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors duration-150">
                            <td class="px-4 py-3">
                                <div class="flex items-center gap-3">
                                    @if ($user->photo)
                                        <img src="{{ asset($user->photo) }}" alt="Avatar" class="h-10 w-10 rounded-full object-cover shadow-md" />
                                    @else
                                        <div class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-br from-blue-400 to-blue-600 text-sm font-semibold text-white shadow-md">
                                            {{ strtoupper(substr($user->prenom ?? $user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-medium text-gray-900">{{ $user->prenom }} {{ $user->name }}</p>
                                        <p class="text-xs text-gray-500">N° CNI : {{ $user->numero_cni }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <p class="text-gray-700">{{ $user->email }}</p>
                                <p class="text-xs text-gray-500">{{ $user->telephone }}</p>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $roleColors = [
                                        'ADMIN' => 'bg-red-100 text-red-700 border-red-300',
                                        'PRATICIEN' => 'bg-indigo-100 text-indigo-700 border-indigo-300',
                                        'SECRETAIRE' => 'bg-sky-100 text-sky-700 border-sky-300',
                                        'PATIENT' => 'bg-emerald-100 text-emerald-700 border-emerald-300',
                                    ];
                                @endphp
                                <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold border {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-600' }}">
                                    <span class="w-2 h-2 rounded-full bg-current opacity-50"></span>
                                    {{ ucfirst(strtolower($user->role)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $statusColors = [
                                        'ACTIF' => 'bg-emerald-100 text-emerald-700 border-emerald-300',
                                        'SUSPENDU' => 'bg-amber-100 text-amber-700 border-amber-300',
                                        'DESACTIVE' => 'bg-rose-100 text-rose-700 border-rose-300',
                                    ];
                                @endphp
                                <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold border {{ $statusColors[$user->statut_compte] ?? 'bg-gray-100 text-gray-600' }}">
                                    <span class="w-2 h-2 rounded-full bg-current"></span>
                                    {{ ucfirst(strtolower($user->statut_compte)) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-600">{{ $user->created_at->format('d/m/Y') }}</td>
                            <td class="px-4 py-3">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.show', $user) }}" class="rounded-lg border border-gray-200 px-3 py-2 text-xs font-medium text-gray-600 hover:bg-gray-100 shadow-md transition-all duration-200 hover:shadow-lg hover:scale-105">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        Voir
                                    </a>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="rounded-lg border border-blue-500 px-3 py-2 text-xs font-medium text-blue-600 hover:bg-blue-50 shadow-md transition-all duration-200 hover:shadow-lg hover:scale-105">
                                        <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        Modifier
                                    </a>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Supprimer cet utilisateur ?');" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rounded-lg border border-red-500 px-3 py-2 text-xs font-medium text-red-600 hover:bg-red-50 shadow-md transition-all duration-200 hover:shadow-lg hover:scale-105">
                                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-10 text-center text-sm text-gray-500">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    Aucun utilisateur ne correspond à votre recherche.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="flex flex-col items-center justify-between gap-3 border-t border-gray-100 px-4 py-4 text-sm text-gray-500 md:flex-row bg-gray-50">
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Affichage de {{ $users->firstItem() ?? 0 }} à {{ $users->lastItem() ?? 0 }} sur {{ $users->total() }} utilisateurs
            </div>
            <div>
                {{ $users->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
