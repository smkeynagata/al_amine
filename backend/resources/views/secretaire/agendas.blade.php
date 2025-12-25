@extends('secretaire.layouts.app')

@section('title', 'Agendas des Praticiens')

@section('content')
<div class="mb-6 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 rounded-2xl p-6 text-white shadow-xl">
    <h2 class="text-2xl font-bold mb-2 flex items-center gap-2">
        <span class="text-3xl">📅</span>
        Agendas des Praticiens
    </h2>
    <p class="text-purple-100">Consultez et gérez les agendas de tous les praticiens</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @foreach($praticiens as $praticien)
    <div class="bg-white rounded-2xl p-6 shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-purple-300 transform hover:-translate-y-2">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 text-white rounded-2xl flex items-center justify-center font-bold text-2xl shadow-lg">
                {{ substr($praticien->user->prenom, 0, 1) }}{{ substr($praticien->user->name, 0, 1) }}
            </div>
            <div class="flex-1">
                <h3 class="font-bold text-lg text-gray-900">Dr. {{ $praticien->user->nom_complet }}</h3>
                <p class="text-sm text-gray-600">{{ $praticien->service->nom }}</p>
            </div>
        </div>

        <div class="space-y-2 mb-4">
            @foreach($praticien->specialites as $specialite)
            <span class="inline-flex items-center px-3 py-1 bg-purple-100 text-purple-800 rounded-full text-xs font-semibold">
                {{ $specialite->nom }}
            </span>
            @endforeach
        </div>

        <div class="flex items-center gap-2 text-sm text-gray-600 mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
            </svg>
            <span>{{ $praticien->user->telephone }}</span>
        </div>

        <a href="{{ route('secretaire.agenda.praticien', $praticien) }}" class="block w-full text-center px-4 py-3 bg-gradient-to-r from-purple-600 to-pink-600 text-white rounded-xl font-semibold hover:shadow-lg transform hover:scale-105 transition-all">
            Voir l'agenda
        </a>
    </div>
    @endforeach
</div>

@if($praticiens->isEmpty())
<div class="bg-white rounded-2xl p-12 shadow-lg text-center">
    <svg class="w-20 h-20 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
    </svg>
    <p class="text-gray-500 text-lg">Aucun praticien trouvé</p>
</div>
@endif
@endsection
