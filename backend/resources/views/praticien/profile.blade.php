@extends('praticien.layouts.app')

@section('title', 'Mon Profil')

@section('content')
<!-- Profile Header -->
<div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg shadow-lg p-8 mb-8 text-white">
    <div class="flex items-center space-x-6">
        <div class="w-32 h-32 rounded-full bg-white flex items-center justify-center text-blue-600 text-5xl font-bold shadow-xl">
            {{ substr(auth()->user()->prenom, 0, 1) }}{{ substr(auth()->user()->name, 0, 1) }}
        </div>
        <div class="flex-1">
            <h1 class="text-4xl font-bold mb-2">Dr. {{ auth()->user()->nom_complet }}</h1>
            <p class="text-xl text-blue-100 mb-2">{{ auth()->user()->praticien->specialites->first()->nom ?? 'Praticien' }}</p>
            <div class="flex items-center space-x-4 text-sm">
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    {{ auth()->user()->email }}
                </span>
                <span class="flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                    </svg>
                    {{ auth()->user()->telephone }}
                </span>
            </div>
        </div>
        <div class="text-right">
            <div class="bg-white bg-opacity-20 backdrop-blur rounded-lg px-6 py-4">
                <p class="text-sm text-blue-100">Expérience</p>
                <p class="text-3xl font-bold">{{ auth()->user()->praticien->annees_experience }} ans</p>
            </div>
        </div>
    </div>
</div>

<!-- Tabs Navigation -->
<div class="bg-white rounded-t-lg shadow-sm border-b" x-data="{ activeTab: 'infos' }">
    <div class="flex space-x-1 p-2">
        <button @click="activeTab = 'infos'" :class="activeTab === 'infos' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100'" class="flex-1 px-4 py-2 rounded-lg font-semibold transition">
            📋 Informations personnelles
        </button>
        <button @click="activeTab = 'professionnel'" :class="activeTab === 'professionnel' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100'" class="flex-1 px-4 py-2 rounded-lg font-semibold transition">
            👨‍⚕️ Informations professionnelles
        </button>
        <button @click="activeTab = 'stats'" :class="activeTab === 'stats' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100'" class="flex-1 px-4 py-2 rounded-lg font-semibold transition">
            📊 Statistiques
        </button>
        <button @click="activeTab = 'securite'" :class="activeTab === 'securite' ? 'bg-blue-600 text-white' : 'text-gray-600 hover:bg-gray-100'" class="flex-1 px-4 py-2 rounded-lg font-semibold transition">
            🔒 Sécurité
        </button>
    </div>

    <!-- Tab Content -->
    <div class="bg-white rounded-b-lg shadow-sm p-6">
        <!-- Informations personnelles -->
        <div x-show="activeTab === 'infos'" x-transition>
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Informations personnelles</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Nom complet</p>
                    <p class="text-lg font-bold text-gray-800">{{ auth()->user()->prenom }} {{ auth()->user()->name }}</p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Date de naissance</p>
                    @if(auth()->user()->date_naissance)
                        <p class="text-lg font-bold text-gray-800">{{ auth()->user()->date_naissance->format('d/m/Y') }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()->date_naissance->age }} ans</p>
                    @else
                        <p class="text-lg font-bold text-gray-500">Non renseignée</p>
                    @endif
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Sexe</p>
                    <p class="text-lg font-bold text-gray-800">{{ auth()->user()->sexe === 'M' ? 'Masculin' : 'Féminin' }}</p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">CNI</p>
                    <p class="text-lg font-bold text-gray-800">{{ auth()->user()->numero_cni }}</p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Téléphone</p>
                    <p class="text-lg font-bold text-gray-800">{{ auth()->user()->telephone }}</p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Email</p>
                    <p class="text-lg font-bold text-gray-800">{{ auth()->user()->email }}</p>
                </div>

                <div class="bg-gray-50 rounded-lg p-4 md:col-span-2">
                    <p class="text-sm text-gray-600 mb-1">Adresse complète</p>
                    <p class="text-lg font-bold text-gray-800">{{ auth()->user()->adresse }}</p>
                    <p class="text-sm text-gray-600">{{ auth()->user()->quartier }}, {{ auth()->user()->ville }}</p>
                </div>
            </div>
        </div>

        <!-- Informations professionnelles -->
        <div x-show="activeTab === 'professionnel'" x-transition class="hidden">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Informations professionnelles</h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <p class="text-sm text-blue-600 mb-1">Service</p>
                    <p class="text-lg font-bold text-gray-800">{{ auth()->user()->praticien->service->nom }}</p>
                </div>

                <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                    <p class="text-sm text-purple-600 mb-1">Numéro d'ordre</p>
                    <!-- Numéro d'ordre supprimé -->
                </div>

                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <p class="text-sm text-green-600 mb-1">Tarif consultation</p>
                    <p class="text-lg font-bold text-gray-800">{{ number_format(auth()->user()->praticien->tarif_consultation, 0, ',', ' ') }} FCFA</p>
                </div>

                <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                    <p class="text-sm text-orange-600 mb-1">Années d'expérience</p>
                    <p class="text-lg font-bold text-gray-800">{{ auth()->user()->praticien->annees_experience }} ans</p>
                </div>

                @if(auth()->user()->praticien->numero_bureau)
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-sm text-gray-600 mb-1">Bureau</p>
                    <p class="text-lg font-bold text-gray-800">{{ auth()->user()->praticien->numero_bureau }}</p>
                </div>
                @endif
            </div>

            <!-- Spécialités -->
            <div class="mt-6">
                <h4 class="text-xl font-bold text-gray-800 mb-4">Mes spécialités</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach(auth()->user()->praticien->specialites as $specialite)
                    <div class="border-l-4 border-blue-500 bg-blue-50 rounded-lg p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-2xl mb-2">{{ $specialite->icone }}</p>
                                <p class="font-bold text-gray-800">{{ $specialite->nom }}</p>
                                <p class="text-xs text-gray-600 mt-1">{{ $specialite->pivot->niveau_expertise }}</p>
                            </div>
                            @if($specialite->pivot->est_principale)
                            <span class="px-2 py-1 bg-blue-600 text-white text-xs rounded-full">Principale</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            @if(auth()->user()->praticien->biographie)
            <div class="mt-6">
                <h4 class="text-xl font-bold text-gray-800 mb-4">Biographie</h4>
                <div class="bg-gray-50 rounded-lg p-4">
                    <p class="text-gray-700">{{ auth()->user()->praticien->biographie }}</p>
                </div>
            </div>
            @endif
        </div>

        <!-- Statistiques -->
        <div x-show="activeTab === 'stats'" x-transition style="display: none;">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Mes statistiques</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-blue-100 text-sm mb-1">Total consultations</p>
                            <p class="text-4xl font-bold">{{ $stats['total_consultations'] ?? 0 }}</p>
                        </div>
                        <svg class="w-12 h-12 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-green-100 text-sm mb-1">Patients suivis</p>
                            <p class="text-4xl font-bold">{{ $stats['total_patients'] ?? 0 }}</p>
                        </div>
                        <svg class="w-12 h-12 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-purple-100 text-sm mb-1">Ce mois</p>
                            <p class="text-4xl font-bold">{{ $stats['consultations_mois'] ?? 0 }}</p>
                        </div>
                        <svg class="w-12 h-12 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gray-50 rounded-lg p-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4">Activité récente</h4>
                <p class="text-gray-500 text-center py-8">Graphique d'activité à venir</p>
            </div>
        </div>

        <!-- Sécurité -->
        <div x-show="activeTab === 'securite'" x-transition style="display: none;">
            <h3 class="text-2xl font-bold text-gray-800 mb-6">Sécurité du compte</h3>

            <!-- Change Password -->
            <div class="bg-white border border-gray-200 rounded-lg p-6 mb-6">
                <h4 class="text-lg font-bold text-gray-800 mb-4">Changer le mot de passe</h4>

                <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Mot de passe actuel</label>
                        <input type="password" name="current_password" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                        @error('current_password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nouveau mot de passe</label>
                        <input type="password" name="password" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                        @error('password')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Confirmer le mot de passe</label>
                        <input type="password" name="password_confirmation" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                    </div>

                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-semibold">
                        Mettre à jour le mot de passe
                    </button>
                </form>
            </div>

            <!-- Account Status -->
            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h4 class="text-lg font-bold text-green-800 mb-2">Statut du compte</h4>
                        <p class="text-sm text-green-700">Votre compte est <strong>{{ auth()->user()->statut_compte }}</strong></p>
                        <p class="text-xs text-green-600 mt-1">Dernière connexion: {{ now()->format('d/m/Y à H:i') }}</p>
                    </div>
                    <div class="bg-green-600 text-white rounded-full w-16 h-16 flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

