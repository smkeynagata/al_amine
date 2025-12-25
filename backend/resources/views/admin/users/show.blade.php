@extends('layouts.app')

@section('title', 'Détails utilisateur')
@section('page-title', 'Profil utilisateur')
@section('breadcrumb', 'Utilisateurs / Profil')

@section('sidebar')
@include('admin.partials.sidebar')
@endsection

@section('content')
<div class="space-y-6">
    <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h1 class="text-2xl font-semibold text-gray-900">{{ $user->prenom }} {{ $user->name }}</h1>
            <p class="text-sm text-gray-500">Compte {{ strtolower($user->role) }} créé le {{ $user->created_at->format('d/m/Y') }}</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.users.edit', $user) }}" class="rounded-lg border border-blue-500 px-4 py-2 text-sm font-medium text-blue-600 hover:bg-blue-50">Modifier</a>
            <a href="{{ route('admin.users.index') }}" class="rounded-lg border border-gray-200 px-4 py-2 text-sm font-medium text-gray-600 hover:bg-gray-100">Retour à la liste</a>
        </div>
    </div>

    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="lg:col-span-2 space-y-6">
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center gap-4">
                    @if($user->photo)
                        <img src="{{ asset($user->photo) }}" alt="Avatar" class="h-20 w-20 rounded-full object-cover" />
                    @else
                        <div class="flex h-20 w-20 items-center justify-center rounded-full bg-blue-100 text-2xl font-semibold text-blue-600">
                            {{ strtoupper(substr($user->prenom ?? $user->name, 0, 1)) }}
                        </div>
                    @endif
                    <div>
                        <p class="text-lg font-semibold text-gray-900">{{ $user->prenom }} {{ $user->name }}</p>
                        <p class="text-sm text-gray-500">{{ $user->email }}</p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            <span class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-xs font-medium text-gray-600">Rôle : {{ ucfirst(strtolower($user->role)) }}</span>
                            <span class="inline-flex items-center rounded-full px-3 py-1 text-xs font-medium {{ $user->statut_compte === 'ACTIF' ? 'bg-emerald-100 text-emerald-700' : ($user->statut_compte === 'SUSPENDU' ? 'bg-amber-100 text-amber-700' : 'bg-rose-100 text-rose-700') }}">
                                Statut : {{ ucfirst(strtolower($user->statut_compte)) }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div class="rounded-lg bg-gray-50 p-4">
                        <h3 class="text-xs font-semibold uppercase text-gray-500">Coordonnées</h3>
                        <ul class="mt-3 space-y-2 text-sm text-gray-700">
                            <li><span class="font-medium">Téléphone :</span> {{ $user->telephone }}</li>
                            <li><span class="font-medium">Adresse :</span> {{ $user->adresse ?? '—' }}</li>
                            <li><span class="font-medium">Quartier :</span> {{ $user->quartier ?? '—' }}</li>
                            <li><span class="font-medium">Ville :</span> {{ $user->ville ?? '—' }}</li>
                        </ul>
                    </div>
                    <div class="rounded-lg bg-gray-50 p-4">
                        <h3 class="text-xs font-semibold uppercase text-gray-500">Informations administratives</h3>
                        <ul class="mt-3 space-y-2 text-sm text-gray-700">
                            <li><span class="font-medium">Date de naissance :</span> {{ optional($user->date_naissance)->format('d/m/Y') ?? '—' }}</li>
                            <li><span class="font-medium">Sexe :</span> {{ $user->sexe === 'M' ? 'Masculin' : ($user->sexe === 'F' ? 'Féminin' : '—') }}</li>
                            <li><span class="font-medium">Numéro CNI :</span> {{ $user->numero_cni }}</li>
                            <li><span class="font-medium">Email vérifié :</span> {{ $user->email_verified_at ? $user->email_verified_at->format('d/m/Y H:i') : 'Non' }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            @if ($user->role === 'PATIENT' && $user->patient)
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900">Dossier patient</h2>
                    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <p class="text-sm text-gray-500">N° sécurité sociale</p>
                            <p class="text-base font-medium text-gray-900">{{ $user->patient->numero_securite_sociale ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Mutuelle</p>
                            <p class="text-base font-medium text-gray-900">{{ $user->patient->mutuelle ?? '—' }}</p>
                        </div>
                    </div>
                    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <p class="text-sm text-gray-500">Allergies</p>
                            <p class="text-base text-gray-700">{{ $user->patient->allergies ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Antécédents</p>
                            <p class="text-base text-gray-700">{{ $user->patient->antecedents ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if ($user->role === 'PRATICIEN' && $user->praticien)
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900">Profil praticien</h2>
                    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <p class="text-sm text-gray-500">Service</p>
                            <p class="text-base font-medium text-gray-900">{{ optional($user->praticien->service)->libelle ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Numéro d'ordre</p>
                            <!-- Numéro d'ordre supprimé -->
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Tarif consultation</p>
                            <p class="text-base font-medium text-gray-900">{{ $user->praticien->tarif_consultation ? number_format($user->praticien->tarif_consultation, 0, ',', ' ') . ' FCFA' : '—' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Expérience</p>
                            <p class="text-base font-medium text-gray-900">{{ $user->praticien->annees_experience ?? 0 }} ans</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-500">Spécialités</p>
                        <div class="mt-2 flex flex-wrap gap-2">
                            @forelse($user->praticien->specialites as $specialite)
                                <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-600">{{ $specialite->libelle }}</span>
                            @empty
                                <p class="text-sm text-gray-500">Aucune spécialité associée.</p>
                            @endforelse
                        </div>
                    </div>
                    <div class="mt-4">
                        <p class="text-sm text-gray-500">Biographie</p>
                        <p class="text-base text-gray-700 whitespace-pre-line">{{ $user->praticien->biographie ?? '—' }}</p>
                    </div>
                </div>
            @endif

            @if ($user->role === 'SECRETAIRE' && $user->secretaire)
                <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                    <h2 class="text-lg font-semibold text-gray-900">Informations secrétaire</h2>
                    <div class="mt-4 grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <p class="text-sm text-gray-500">Matricule</p>
                            <p class="text-base font-medium text-gray-900">{{ $user->secretaire->matricule ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Date d'ajout</p>
                            <p class="text-base font-medium text-gray-900">{{ optional($user->secretaire->created_at)->format('d/m/Y') ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <div class="space-y-6">
            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900">Journal d'activité</h2>
                <p class="text-sm text-gray-500">Derniers mouvements associés à ce compte.</p>
                <ul class="mt-4 space-y-3 text-sm text-gray-600">
                    @forelse($user->auditTrails()->latest()->take(10)->get() as $audit)
                        <li class="flex gap-3">
                            <span class="mt-1 inline-block h-2 w-2 rounded-full bg-blue-500"></span>
                            <div>
                                <p class="font-medium text-gray-800">{{ $audit->action }}</p>
                                <p class="text-xs text-gray-500">{{ $audit->created_at->diffForHumans() }} - {{ $audit->description }}</p>
                            </div>
                        </li>
                    @empty
                        <li class="text-sm text-gray-500">Aucune activité récente enregistrée.</li>
                    @endforelse
                </ul>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900">Gestion rapide</h2>
                <div class="mt-4 space-y-3 text-sm">
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Confirmer la suppression définitive de ce compte ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full rounded-lg border border-red-500 px-4 py-2 font-medium text-red-600 hover:bg-red-50">Supprimer le compte</button>
                    </form>
                    <a href="mailto:{{ $user->email }}" class="block w-full rounded-lg border border-gray-200 px-4 py-2 text-center font-medium text-gray-600 hover:bg-gray-100">Contacter par email</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
