@extends('layouts.app')

@section('title', 'Modifier un utilisateur')
@section('page-title', 'Modifier un utilisateur')
@section('breadcrumb', 'Utilisateurs / Modifier')

@section('sidebar')
@include('admin.partials.sidebar')
@endsection

@section('content')
<div class="space-y-6">
    <div>
        <h1 class="text-2xl font-semibold text-gray-900">Mettre à jour {{ $user->prenom }} {{ $user->name }}</h1>
        <p class="text-sm text-gray-500">Ajustez les informations de base et le statut du compte. Les données métier spécifiques sont gérées depuis leurs modules dédiés.</p>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900">Informations personnelles</h2>
                <div class="mt-4 space-y-4">
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-xs font-medium uppercase text-gray-500">Prénom *</label>
                            <input type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" required>
                            @error('prenom')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="text-xs font-medium uppercase text-gray-500">Nom *</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" required>
                            @error('name')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-xs font-medium uppercase text-gray-500">Email *</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" required>
                            @error('email')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="text-xs font-medium uppercase text-gray-500">Téléphone *</label>
                            <input type="text" name="telephone" value="{{ old('telephone', $user->telephone) }}" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" required>
                            @error('telephone')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-xs font-medium uppercase text-gray-500">Date de naissance</label>
                            <input type="date" name="date_naissance" value="{{ old('date_naissance', optional($user->date_naissance)->format('Y-m-d')) }}" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                        <div>
                            <label class="text-xs font-medium uppercase text-gray-500">Sexe</label>
                            <select name="sexe" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                                <option value="">Choisir...</option>
                                <option value="M" @selected(old('sexe', $user->sexe) === 'M')>Masculin</option>
                                <option value="F" @selected(old('sexe', $user->sexe) === 'F')>Féminin</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-medium uppercase text-gray-500">Numéro CNI *</label>
                        <input type="text" name="numero_cni" value="{{ old('numero_cni', $user->numero_cni) }}" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" required>
                        @error('numero_cni')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="text-xs font-medium uppercase text-gray-500">Nouveau mot de passe</label>
                        <input type="password" name="password" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" placeholder="Laisser vide pour conserver l'actuel">
                        @error('password')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900">Coordonnées & statut</h2>
                <div class="mt-4 space-y-4">
                    <div>
                        <label class="text-xs font-medium uppercase text-gray-500">Adresse</label>
                        <textarea name="adresse" rows="3" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">{{ old('adresse', $user->adresse) }}</textarea>
                    </div>
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="text-xs font-medium uppercase text-gray-500">Quartier</label>
                            <select name="quartier" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                                <option value="">Sélectionner</option>
                                @foreach($quartiers as $quartier)
                                    <option value="{{ $quartier }}" @selected(old('quartier', $user->quartier) === $quartier)>{{ $quartier }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-medium uppercase text-gray-500">Ville</label>
                            <input type="text" name="ville" value="{{ old('ville', $user->ville) }}" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none">
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-medium uppercase text-gray-500">Rôle</label>
                        <input type="text" value="{{ ucfirst(strtolower($user->role)) }}" class="mt-1 w-full rounded-lg border border-gray-200 bg-gray-100 px-3 py-2 text-sm text-gray-600" disabled>
                        <p class="mt-1 text-xs text-gray-400">Le rôle ne peut pas être modifié ici.</p>
                    </div>

                    <div>
                        <label class="text-xs font-medium uppercase text-gray-500">Statut du compte *</label>
                        <select name="statut_compte" class="mt-1 w-full rounded-lg border border-gray-200 px-3 py-2 text-sm focus:border-blue-500 focus:outline-none" required>
                            <option value="ACTIF" @selected(old('statut_compte', $user->statut_compte) === 'ACTIF')>Actif</option>
                            <option value="SUSPENDU" @selected(old('statut_compte', $user->statut_compte) === 'SUSPENDU')>Suspendu</option>
                            <option value="DESACTIVE" @selected(old('statut_compte', $user->statut_compte) === 'DESACTIVE')>Désactivé</option>
                        </select>
                        @error('statut_compte')<p class="mt-1 text-xs text-red-500">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('admin.users.index') }}" class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">Annuler</a>
            <button type="submit" class="rounded-lg bg-blue-600 px-5 py-2 text-sm font-semibold text-white shadow hover:bg-blue-700">Enregistrer</button>
        </div>
    </form>
</div>
@endsection
