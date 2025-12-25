@extends('praticien.layouts.app')

@section('title', 'Mes Patients')

@section('content')
<!-- Stats Cards -->
<div class="grid grid-cols-3 gap-6 mb-8">
    <div class="bg-white rounded-2xl p-6 shadow-elegant">
        <div class="text-4xl font-bold text-gray-900 mb-1">{{ $stats['total_patients'] }}</div>
        <div class="text-sm text-gray-500">Total patients</div>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-elegant">
        <div class="text-4xl font-bold text-gray-900 mb-1">{{ $stats['nouveaux_mois'] }}</div>
        <div class="text-sm text-gray-500">Nouveaux ce mois</div>
    </div>
    <div class="bg-white rounded-2xl p-6 shadow-elegant">
        <div class="text-4xl font-bold text-gray-900 mb-1">{{ $stats['consultations_total'] }}</div>
        <div class="text-sm text-gray-500">Consultations totales</div>
    </div>
</div>

<!-- Patients List -->
<div class="bg-white rounded-2xl p-6 shadow-elegant">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-bold text-gray-900">Liste des patients</h2>
        <select class="bg-gray-100 rounded-lg px-4 py-2 text-sm font-medium focus:outline-none">
            <option>Tous les patients</option>
            <option>Actifs</option>
            <option>Récents</option>
        </select>
    </div>

    @if($patients->isNotEmpty())
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead>
                <tr class="border-b border-gray-200">
                    <th class="text-left py-4 px-4 text-sm font-semibold text-gray-600">Patient</th>
                    <th class="text-left py-4 px-4 text-sm font-semibold text-gray-600">Contact</th>
                    <th class="text-left py-4 px-4 text-sm font-semibold text-gray-600">Consultations</th>
                    <th class="text-left py-4 px-4 text-sm font-semibold text-gray-600">Dernière visite</th>
                    <th class="text-right py-4 px-4 text-sm font-semibold text-gray-600">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($patients as $patient)
                <tr class="border-b border-gray-100 hover:bg-gray-50 transition">
                    <td class="py-4 px-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-400 to-pink-400 rounded-full flex items-center justify-center text-white font-bold">
                                {{ substr($patient->user->prenom, 0, 1) }}{{ substr($patient->user->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900">{{ $patient->user->nom_complet }}</div>
                                <div class="text-xs text-gray-500">{{ $patient->date_naissance ? \Carbon\Carbon::parse($patient->date_naissance)->age . ' ans' : 'N/A' }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-4 px-4">
                        <div class="text-sm text-gray-600">{{ $patient->user->telephone ?? 'N/A' }}</div>
                        <div class="text-xs text-gray-500">{{ $patient->user->email }}</div>
                    </td>
                    <td class="py-4 px-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                            {{ $patient->consultations->count() }} consultations
                        </span>
                    </td>
                    <td class="py-4 px-4 text-sm text-gray-600">
                        {{ $patient->consultations->sortByDesc('date_consultation')->first()?->date_consultation?->format('d/m/Y') ?? 'N/A' }}
                    </td>
                    <td class="py-4 px-4 text-right">
                        <a href="{{ route('praticien.patient.dossier', $patient) }}" class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-purple-600 to-blue-600 text-white rounded-lg text-sm font-medium hover:opacity-90 transition">
                            Voir dossier
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-12">
        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
        </svg>
        <p class="text-gray-500">Aucun patient pour le moment</p>
    </div>
    @endif
</div>
@endsection

