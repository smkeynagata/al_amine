@extends('layouts.app')

@section('title', 'Ajouter un utilisateur')
@section('page-title', 'Nouvel utilisateur')
@section('breadcrumb', 'Utilisateurs / Créer')

@section('sidebar')
@include('admin.partials.sidebar')
@endsection

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Créer un nouvel utilisateur</h1>
        <p class="text-sm text-gray-500">Renseignez les informations générales puis complétez les données spécifiques à son rôle.</p>
    </div>

    <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-6" x-data="{ role: '{{ old('role', 'PATIENT') }}' }">
        @csrf

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900">Identité</h2>
                <div class="mt-4 space-y-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-xs font-medium uppercase text-gray-500">Prénom *</label>
                            <input type="text" name="prenom" value="{{ old('prenom') }}" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" required>
                            @error('prenom')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="text-xs font-medium uppercase text-gray-500">Nom *</label>
                            <input type="text" name="name" value="{{ old('name') }}" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" required>
                            @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-xs font-medium uppercase text-gray-500">Email *</label>
                            <input type="email" name="email" value="{{ old('email') }}" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" required>
                            @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="text-xs font-medium uppercase text-gray-500">Téléphone *</label>
                            <input type="text" name="telephone" value="{{ old('telephone') }}" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" required>
                            @error('telephone')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-xs font-medium uppercase text-gray-500">Date de naissance *</label>
                            <input type="date" name="date_naissance" value="{{ old('date_naissance') }}" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" required>
                            @error('date_naissance')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="text-xs font-medium uppercase text-gray-500">Sexe *</label>
                            <select name="sexe" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" required>
                                <option value="">Choisir...</option>
                                <option value="M" @selected(old('sexe') === 'M')>Masculin</option>
                                <option value="F" @selected(old('sexe') === 'F')>Féminin</option>
                            </select>
                            @error('sexe')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-medium uppercase text-gray-500">Numéro CNI *</label>
                        <input type="text" name="numero_cni" value="{{ old('numero_cni') }}" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" required>
                        @error('numero_cni')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="text-xs font-medium uppercase text-gray-500">Mot de passe *</label>
                        <input type="password" name="password" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" required>
                        @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900">Coordonnées</h2>
                <div class="mt-4 space-y-4">
                    <div>
                        <label class="text-xs font-medium uppercase text-gray-500">Adresse</label>
                        <textarea name="adresse" rows="3" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" placeholder="Adresse complète">{{ old('adresse') }}</textarea>
                        @error('adresse')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-xs font-medium uppercase text-gray-500">Quartier</label>
                            <select name="quartier" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                                <option value="">Sélectionner</option>
                                @foreach($quartiers as $quartier)
                                    <option value="{{ $quartier }}" @selected(old('quartier') === $quartier)>{{ $quartier }}</option>
                                @endforeach
                            </select>
                            @error('quartier')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="text-xs font-medium uppercase text-gray-500">Ville *</label>
                            <input type="text" name="ville" value="{{ old('ville', 'Dakar') }}" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" required>
                            @error('ville')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-medium uppercase text-gray-500">Rôle *</label>
                        <select name="role" x-model="role" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" required>
                            <option value="PATIENT">Patient</option>
                            <option value="PRATICIEN">Praticien</option>
                            <option value="SECRETAIRE">Secrétaire</option>
                            <option value="ADMIN">Administrateur</option>
                        </select>
                        @error('role')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Sections spécifiques -->
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm" x-show="role === 'PATIENT'">
            <h2 class="text-lg font-semibold text-gray-900">Informations patient</h2>
            <p class="text-sm text-gray-500">Données médicales de base.</p>
            <div class="mt-4 space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-medium uppercase text-gray-500">Numéro sécurité sociale</label>
                        <input type="text" name="numero_securite_sociale" value="{{ old('numero_securite_sociale') }}" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    </div>
                    <div>
                        <label class="text-xs font-medium uppercase text-gray-500">Mutuelle</label>
                        <input type="text" name="mutuelle" value="{{ old('mutuelle') }}" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-medium uppercase text-gray-500">Allergies</label>
                    <textarea name="allergies" rows="3" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">{{ old('allergies') }}</textarea>
                </div>
                <div>
                    <label class="text-xs font-medium uppercase text-gray-500">Antécédents</label>
                    <textarea name="antecedents" rows="3" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">{{ old('antecedents') }}</textarea>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm" x-show="role === 'PRATICIEN'">
            <h2 class="text-lg font-semibold text-gray-900">Informations praticien</h2>
            <p class="text-sm text-gray-500">Détails professionnels et spécialisations.</p>
            <div class="mt-4 space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-medium uppercase text-gray-500">Service *</label>
                        <select name="service_id" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" :required="role === 'PRATICIEN'">
                            <option value="">Choisir un service</option>
                            @foreach($services as $service)
                                <option value="{{ $service->id }}" @selected(old('service_id') == $service->id)>{{ $service->nom }}</option>
                            @endforeach
                        </select>
                        @error('service_id')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <!-- Champ Numéro d'ordre supprimé -->
                </div>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <label class="text-xs font-medium uppercase text-gray-500">Tarif consultation *</label>
                        <input type="number" min="0" step="100" name="tarif_consultation" value="{{ old('tarif_consultation') }}" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" :required="role === 'PRATICIEN'">
                        @error('tarif_consultation')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="text-xs font-medium uppercase text-gray-500">Années d'expérience</label>
                        <input type="number" min="0" name="annees_experience" value="{{ old('annees_experience') }}" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                    </div>
                </div>
                <div>
                    <label class="text-xs font-medium uppercase text-gray-500">Biographie</label>
                    <textarea name="biographie" rows="4" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">{{ old('biographie') }}</textarea>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm" x-show="role === 'SECRETAIRE'">
            <h2 class="text-lg font-semibold text-gray-900">Informations secrétaire</h2>
            <p class="text-sm text-gray-500">Assignation éventuelle d'un matricule.</p>
            <div class="mt-4">
                <label class="text-xs font-medium uppercase text-gray-500">Matricule</label>
                <input type="text" name="matricule" value="{{ old('matricule') }}" placeholder="Si vide, un matricule sera généré automatiquement" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.users.index') }}" class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">Annuler</a>
            <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-semibold text-white shadow hover:bg-blue-700">Enregistrer</button>
        </div>
    </form>
</div>
@endsection
