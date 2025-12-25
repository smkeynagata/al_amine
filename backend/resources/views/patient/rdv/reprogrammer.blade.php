@extends('layouts.app')

@section('title', 'Reprogrammer Rendez-vous')
@section('page-title', 'Reprogrammer mon rendez-vous')

@section('sidebar')
    @include('patient.partials.sidebar')
@endsection

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('patient.mes-rdv') }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
            <i class="fas fa-arrow-left mr-2"></i>
            Retour à mes rendez-vous
        </a>
    </div>

    <!-- Current RDV Info -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-4">Rendez-vous actuel</h2>
        
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600">Praticien</p>
                    <p class="font-semibold text-gray-900">Dr. {{ $rendezVous->praticien->user->nom_complet }}</p>
                    <p class="text-sm text-gray-500">{{ $rendezVous->praticien->specialites->pluck('nom')->implode(', ') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-600">Date et heure actuelles</p>
                    <p class="font-semibold text-gray-900">
                        {{ $rendezVous->date_heure_rdv->locale('fr')->isoFormat('dddd D MMMM YYYY') }}
                    </p>
                    <p class="text-sm text-gray-500">
                        <i class="fas fa-clock mr-1"></i>
                        {{ $rendezVous->date_heure_rdv->format('H:i') }} - {{ $rendezVous->duree_minutes }} minutes
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Reprogramming Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        <h2 class="text-xl font-semibold text-gray-900 mb-6">
            <i class="fas fa-calendar-alt text-blue-600 mr-2"></i>
            Choisir une nouvelle date
        </h2>

        <form method="POST" action="{{ route('patient.rendezvous.update', $rendezVous) }}" x-data="{ selectedDate: '', selectedTime: '' }">
            @csrf
            @method('PATCH')

            <!-- Date Picker -->
            <div class="mb-6">
                <label for="date" class="block text-sm font-medium text-gray-700 mb-2">
                    Nouvelle date <span class="text-red-500">*</span>
                </label>
                <input 
                    type="date" 
                    name="date" 
                    id="date"
                    x-model="selectedDate"
                    min="{{ now()->addDay()->format('Y-m-d') }}"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required
                >
                @error('date')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Time Picker -->
            <div class="mb-6">
                <label for="time" class="block text-sm font-medium text-gray-700 mb-2">
                    Nouvelle heure <span class="text-red-500">*</span>
                </label>
                <input 
                    type="time" 
                    name="time" 
                    id="time"
                    x-model="selectedTime"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    required
                >
                @error('time')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                
                <!-- Combine date and time -->
                <input type="hidden" name="date_heure_rdv" :value="selectedDate + ' ' + selectedTime">
            </div>

            <!-- Available Slots Info -->
            @if($disponibilites->isNotEmpty())
                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                    <h3 class="text-sm font-semibold text-green-900 mb-3">
                        <i class="fas fa-info-circle mr-2"></i>
                        Horaires de disponibilité du praticien
                    </h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        @foreach($disponibilites as $dispo)
                            <div class="text-sm">
                                <p class="font-medium text-green-900">
                                    {{ ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'][$dispo->jour_semaine] }}
                                </p>
                                <p class="text-green-700">
                                    {{ substr($dispo->heure_debut, 0, 5) }} - {{ substr($dispo->heure_fin, 0, 5) }}
                                </p>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Motif (optionnel) -->
            <div class="mb-6">
                <label for="motif" class="block text-sm font-medium text-gray-700 mb-2">
                    Motif (facultatif)
                </label>
                <textarea 
                    name="motif" 
                    id="motif" 
                    rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Si vous souhaitez modifier le motif de consultation..."
                >{{ old('motif', $rendezVous->motif) }}</textarea>
                @error('motif')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Warning -->
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                <div class="flex items-start">
                    <i class="fas fa-exclamation-triangle text-yellow-600 mt-1 mr-3"></i>
                    <div class="text-sm text-yellow-800">
                        <p class="font-medium mb-1">Important</p>
                        <ul class="list-disc ml-5 space-y-1">
                            <li>La reprogrammation est soumise à validation par le praticien</li>
                            <li>Vous recevrez une notification par email une fois validée</li>
                            <li>Essayez de choisir un créneau dans les horaires de disponibilité</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex items-center justify-end gap-4">
                <a href="{{ route('patient.mes-rdv') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-medium transition-colors">
                    Annuler
                </a>
                <button 
                    type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                    <i class="fas fa-check mr-2"></i>
                    Confirmer la reprogrammation
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
