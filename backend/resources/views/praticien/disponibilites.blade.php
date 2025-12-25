@extends('praticien.layouts.app')

@section('title', 'Mes Disponibilités')

@section('content')
<div class="mb-6">
    <h3 class="text-lg font-semibold text-gray-700">Définissez vos plages horaires de consultation</h3>
    <p class="text-sm text-gray-500">Les patients pourront demander des RDV sur ces créneaux</p>
</div>

<!-- Add Availability Form -->
<div class="bg-white rounded-lg shadow-lg p-6 mb-8">
    <h3 class="text-xl font-bold text-gray-800 mb-6">Ajouter une disponibilité</h3>

    <form method="POST" action="{{ route('praticien.disponibilites.store') }}" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Jour de la semaine -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Jour de la semaine</label>
                <select name="jour_semaine" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                    <option value="">Sélectionner un jour</option>
                    <option value="LUNDI">Lundi</option>
                    <option value="MARDI">Mardi</option>
                    <option value="MERCREDI">Mercredi</option>
                    <option value="JEUDI">Jeudi</option>
                    <option value="VENDREDI">Vendredi</option>
                    <option value="SAMEDI">Samedi</option>
                    <option value="DIMANCHE">Dimanche</option>
                </select>
            </div>

            <!-- Type de créneau -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Type de créneau</label>
                <select name="type_creneau" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
                    <option value="MATIN">Matin</option>
                    <option value="APRES_MIDI">Après-midi</option>
                    <option value="JOURNEE">Toute la journée</option>
                </select>
            </div>

            <!-- Heure début -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Heure de début</label>
                <input type="time" name="heure_debut" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Heure fin -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Heure de fin</label>
                <input type="time" name="heure_fin" required class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500">
            </div>
        </div>

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-lg font-semibold">
            ✓ Ajouter cette disponibilité
        </button>
    </form>
</div>

<!-- Current Availabilities -->
<div class="bg-white rounded-lg shadow-lg p-6">
    <h3 class="text-xl font-bold text-gray-800 mb-6">Mes disponibilités actuelles</h3>

    @if(isset($disponibilites) && $disponibilites->isNotEmpty())
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($disponibilites as $dispo)
        <div class="border-l-4 border-blue-500 bg-blue-50 rounded-lg p-4">
            <div class="flex items-center justify-between">
                <div>
                    <h4 class="font-bold text-gray-800">{{ $dispo->jour_semaine }}</h4>
                    <p class="text-sm text-gray-600">{{ $dispo->type_creneau }}</p>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ \Carbon\Carbon::parse($dispo->heure_debut)->format('H:i') }} -
                        {{ \Carbon\Carbon::parse($dispo->heure_fin)->format('H:i') }}
                    </p>
                </div>
                <div class="flex space-x-2">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $dispo->est_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                        {{ $dispo->est_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="text-center py-12">
        <svg class="w-24 h-24 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucune disponibilité définie</h3>
        <p class="text-gray-500">Ajoutez vos plages horaires de consultation</p>
    </div>
    @endif
</div>
@endsection

