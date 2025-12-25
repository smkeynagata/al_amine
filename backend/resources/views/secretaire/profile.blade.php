@extends('secretaire.layouts.app')

@section('title', 'Mon Profil')

@section('content')
<div class="max-w-6xl mx-auto">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Mon Profil</h1>
        <p class="text-gray-600 mt-2">Gérez vos informations personnelles et vos paramètres de sécurité</p>
    </div>

    <!-- Informations Personnelles -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Photo et Infos Rapides -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
                <div class="text-center">
                    <div class="w-24 h-24 mx-auto mb-4 rounded-full flex items-center justify-center bg-gradient-to-br from-blue-400 to-blue-600 text-white text-4xl font-bold">
                        {{ substr(auth()->user()->prenom, 0, 1) }}{{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                    <h2 class="text-xl font-bold text-gray-800">{{ auth()->user()->nom_complet }}</h2>
                    <p class="text-sm text-gray-500 mt-1">{{ auth()->user()->email }}</p>
                    <div class="mt-4 inline-block px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">
                        Secrétaire
                    </div>
                </div>

                <div class="mt-6 pt-6 border-t border-gray-200 space-y-3 text-sm">
                    <div>
                        <p class="text-gray-500">Téléphone</p>
                        <p class="font-semibold text-gray-800">{{ auth()->user()->telephone ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">CNI</p>
                        <p class="font-semibold text-gray-800">{{ auth()->user()->numero_cni ?? 'Non renseigné' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Statut</p>
                        <p class="font-semibold">
                            @php
                                $statusColors = [
                                    'ACTIF' => 'bg-green-100 text-green-700',
                                    'SUSPENDU' => 'bg-yellow-100 text-yellow-700',
                                    'DESACTIVE' => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <span class="inline-block px-2 py-1 rounded text-xs font-semibold {{ $statusColors[auth()->user()->statut_compte] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst(strtolower(auth()->user()->statut_compte)) }}
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire Informations Personnelles -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
                <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Informations Personnelles
                </h3>

                <form method="POST" action="{{ route('secretaire.profile.update') }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Prénom -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Prénom</label>
                            <input type="text" name="prenom" value="{{ old('prenom', auth()->user()->prenom) }}" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required>
                            @error('prenom')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nom -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nom</label>
                            <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                required>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            required>
                        @error('email')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Téléphone -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Téléphone</label>
                            <input type="tel" name="telephone" value="{{ old('telephone', auth()->user()->telephone) }}" 
                                placeholder="77 123 45 67"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('telephone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- CNI -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Numéro CNI</label>
                            <input type="text" value="{{ auth()->user()->numero_cni }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 text-gray-600 cursor-not-allowed"
                                readonly>
                            <p class="text-xs text-gray-400 mt-1">Le numéro CNI est fixé lors de l'inscription et ne peut plus être modifié.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Date de Naissance -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Date de Naissance</label>
                            <input type="date" name="date_naissance" value="{{ old('date_naissance', auth()->user()->date_naissance?->format('Y-m-d')) }}" 
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('date_naissance')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Sexe -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Sexe</label>
                            <select name="sexe" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">-- Sélectionner --</option>
                                <option value="M" {{ auth()->user()->sexe === 'M' ? 'selected' : '' }}>Masculin</option>
                                <option value="F" {{ auth()->user()->sexe === 'F' ? 'selected' : '' }}>Féminin</option>
                            </select>
                            @error('sexe')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Adresse -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Adresse</label>
                        <input type="text" name="adresse" value="{{ old('adresse', auth()->user()->adresse) }}" 
                            placeholder="Adresse complète"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        @error('adresse')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Quartier -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Quartier</label>
                            <select name="quartier" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">-- Sélectionner --</option>
                                @foreach(quartiersDatekar() as $quartier)
                                    <option value="{{ $quartier }}" {{ auth()->user()->quartier === $quartier ? 'selected' : '' }}>{{ $quartier }}</option>
                                @endforeach
                            </select>
                            @error('quartier')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Ville -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Ville</label>
                            <input type="text" name="ville" value="{{ old('ville', auth()->user()->ville) }}" 
                                placeholder="Dakar"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('ville')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <button type="submit" class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition">
                            Enregistrer les modifications
                        </button>
                        @if (session('status') === 'profile-updated')
                            <p class="text-green-600 text-sm font-semibold">✓ Profil mis à jour avec succès</p>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sécurité -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Changer le Mot de Passe -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
                Changer le Mot de Passe
            </h3>

            <form method="POST" action="{{ route('secretaire.profile.password.update') }}" class="space-y-4">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Mot de passe actuel</label>
                    <input type="password" name="current_password" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        required>
                    @error('current_password', 'updatePassword')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nouveau mot de passe</label>
                    <input type="password" name="password" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        required>
                    @error('password', 'updatePassword')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" 
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:border-transparent"
                        required>
                    @error('password_confirmation', 'updatePassword')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                    <button type="submit" class="px-6 py-2 bg-orange-600 text-white font-semibold rounded-lg hover:bg-orange-700 transition">
                        Mettre à jour le mot de passe
                    </button>
                    @if (session('status') === 'password-updated')
                        <p class="text-green-600 text-sm font-semibold">✓ Mot de passe mis à jour</p>
                    @endif
                </div>
            </form>
        </div>

        <!-- Informations de Compte -->
        <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
            <h3 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Informations de Compte
            </h3>

            <div class="space-y-4">
                <div class="p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <p class="text-xs text-gray-600 uppercase tracking-wide">Rôle</p>
                    <p class="text-lg font-bold text-gray-800 mt-1">Secrétaire</p>
                </div>

                <div class="p-4 bg-green-50 rounded-lg border border-green-200">
                    <p class="text-xs text-gray-600 uppercase tracking-wide">Statut du Compte</p>
                    <p class="text-lg font-bold text-gray-800 mt-1">
                        <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                            @if(auth()->user()->statut_compte === 'ACTIF') bg-green-100 text-green-700
                            @elseif(auth()->user()->statut_compte === 'SUSPENDU') bg-yellow-100 text-yellow-700
                            @else bg-red-100 text-red-700
                            @endif">
                            {{ ucfirst(strtolower(auth()->user()->statut_compte)) }}
                        </span>
                    </p>
                </div>

                <div class="p-4 bg-purple-50 rounded-lg border border-purple-200">
                    <p class="text-xs text-gray-600 uppercase tracking-wide">Compte Créé</p>
                    <p class="text-lg font-bold text-gray-800 mt-1">{{ auth()->user()->created_at->format('d/m/Y à H:i') }}</p>
                </div>

                <div class="p-4 bg-indigo-50 rounded-lg border border-indigo-200">
                    <p class="text-xs text-gray-600 uppercase tracking-wide">Dernière Mise à Jour</p>
                    <p class="text-lg font-bold text-gray-800 mt-1">{{ auth()->user()->updated_at->format('d/m/Y à H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="bg-red-50 border border-red-200 rounded-xl shadow-md p-6">
        <h3 class="text-lg font-bold text-red-800 mb-4 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4v2m0 4v2M7.08 6.47a9 9 0 1 1 9.84 0"></path>
            </svg>
            Zone Dangereuse
        </h3>
        <p class="text-red-700 text-sm mb-4">Les actions suivantes sont irréversibles. Procédez avec prudence.</p>
        
        <form method="POST" action="{{ route('secretaire.profile.destroy') }}" onsubmit="return confirm('Êtes-vous sûr ? Cette action est irréversible. Votre compte sera supprimé définitivement.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="px-6 py-2 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition">
                Supprimer mon compte
            </button>
        </form>
    </div>
</div>
@endsection
