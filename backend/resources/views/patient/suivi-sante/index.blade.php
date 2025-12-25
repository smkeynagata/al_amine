@extends('layouts.app')

@section('title', 'Suivi Santé')
@section('page-title', 'Mon Suivi Santé')

@section('sidebar')
    @include('patient.partials.sidebar')
@endsection

@push('styles')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endpush

@section('content')
@if(session('success'))
<div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-lg flex items-center">
    <i class="fas fa-check-circle mr-3"></i>{{ session('success') }}
</div>
@endif

<!-- Statistiques -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Poids actuel</p>
                <p class="text-2xl font-bold text-blue-600">{{ $stats['poids_actuel'] ?? 'N/A' }} <span class="text-sm">kg</span></p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-weight text-blue-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Tension</p>
                <p class="text-2xl font-bold text-red-600">
                    @if($stats['tension_actuelle'])
                        {{ $stats['tension_actuelle']->tension_systolique }}/{{ $stats['tension_actuelle']->tension_diastolique }}
                    @else
                        N/A
                    @endif
                </p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-heartbeat text-red-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Total mesures</p>
                <p class="text-2xl font-bold text-green-600">{{ $stats['total_mesures'] }}</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-chart-line text-green-600 text-xl"></i>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 mb-1">Dernière mesure</p>
                <p class="text-lg font-bold text-purple-600">
                    {{ $stats['derniere_mesure'] ? $stats['derniere_mesure']->format('d/m/Y') : 'Aucune' }}
                </p>
            </div>
            <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-calendar-check text-purple-600 text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Filtres -->
<div class="flex flex-wrap justify-between items-center mb-6 gap-4">
    <form method="GET" class="flex items-center space-x-2">
        <select name="periode" onchange="this.form.submit()" 
                class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
            <option value="1mois" {{ $periode === '1mois' ? 'selected' : '' }}>1 mois</option>
            <option value="3mois" {{ $periode === '3mois' ? 'selected' : '' }}>3 mois</option>
            <option value="6mois" {{ $periode === '6mois' ? 'selected' : '' }}>6 mois</option>
            <option value="1an" {{ $periode === '1an' ? 'selected' : '' }}>1 an</option>
            <option value="tout" {{ $periode === 'tout' ? 'selected' : '' }}>Tout</option>
        </select>
    </form>
    
    <div class="bg-blue-50 border border-blue-200 px-4 py-2 rounded-lg text-sm text-blue-800">
        <i class="fas fa-info-circle mr-2"></i>
        Les mesures sont enregistrées par votre praticien lors des consultations
    </div>
</div>

<!-- Graphiques -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Évolution du poids</h3>
        <canvas id="poidsChart"></canvas>
    </div>
    
    <div class="bg-white rounded-lg shadow-md p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tension artérielle</h3>
        <canvas id="tensionChart"></canvas>
    </div>
</div>

<!-- Liste des mesures -->
<div class="bg-white rounded-lg shadow-md overflow-hidden">
    <div class="px-6 py-4 border-b">
        <h3 class="text-lg font-semibold text-gray-900">Historique des mesures</h3>
    </div>
    
    @if($mesures->isEmpty())
        <div class="p-12 text-center">
            <i class="fas fa-chart-line text-gray-300 text-6xl mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-700 mb-2">Aucune mesure</h3>
            <p class="text-gray-500">Vos mesures de santé seront enregistrées par votre praticien lors de vos consultations</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Poids</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IMC</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tension</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rythme</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Notes</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($mesures as $mesure)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $mesure->date_mesure->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $mesure->poids ?? '-' }} kg</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $mesure->imc ?? '-' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $mesure->tension_systolique && $mesure->tension_diastolique ? $mesure->tension_systolique . '/' . $mesure->tension_diastolique : '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $mesure->frequence_cardiaque ?? '-' }} bpm</td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate">{{ $mesure->notes ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    const chartData = @json($chartData);
    
    // Graphique Poids
    new Chart(document.getElementById('poidsChart'), {
        type: 'line',
        data: {
            labels: chartData.dates,
            datasets: [{
                label: 'Poids (kg)',
                data: chartData.poids,
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.4
            }]
        },
        options: {responsive: true, plugins: {legend: {display: false}}}
    });
    
    // Graphique Tension
    new Chart(document.getElementById('tensionChart'), {
        type: 'line',
        data: {
            labels: chartData.dates,
            datasets: [{
                label: 'Systolique',
                data: chartData.tension_systolique,
                borderColor: 'rgb(239, 68, 68)',
                tension: 0.4
            }, {
                label: 'Diastolique',
                data: chartData.tension_diastolique,
                borderColor: 'rgb(59, 130, 246)',
                tension: 0.4
            }]
        },
        options: {responsive: true}
    });
</script>
@endpush
