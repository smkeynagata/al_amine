@extends('layouts.app')

@section('title', 'Mon Calendrier')
@section('page-title', 'Mon Calendrier')

@section('sidebar')
    @include('patient.partials.sidebar')
@endsection

@push('styles')
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
<style>
    .fc-event {
        cursor: pointer;
    }
    .fc-event:hover {
        opacity: 0.85;
    }
</style>
@endpush

@section('content')
<div class="mb-6 flex justify-between items-center">
    <div>
        <h3 class="text-xl font-semibold text-gray-900">Mon calendrier médical</h3>
        <p class="text-sm text-gray-500">Visualisez tous vos rendez-vous</p>
    </div>
    <div class="flex space-x-3">
        <a href="{{ route('patient.calendrier.export.google') }}" 
           class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center font-medium transition-colors">
            <i class="fab fa-google mr-2"></i>
            Exporter vers Google
        </a>
        <a href="{{ route('patient.calendrier.export.ical') }}" 
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center font-medium transition-colors">
            <i class="fas fa-download mr-2"></i>
            Exporter (.ics)
        </a>
    </div>
</div>

@if(session('error'))
<div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-lg flex items-center">
    <i class="fas fa-exclamation-circle mr-3 text-xl"></i>
    <span>{{ session('error') }}</span>
</div>
@endif

<div class="bg-white rounded-lg shadow-md p-6">
    <!-- Légende -->
    <div class="mb-6 flex flex-wrap gap-4 text-sm">
        <div class="flex items-center">
            <div class="w-4 h-4 bg-blue-500 rounded mr-2"></div>
            <span class="text-gray-700">Confirmé</span>
        </div>
        <div class="flex items-center">
            <div class="w-4 h-4 bg-yellow-500 rounded mr-2"></div>
            <span class="text-gray-700">En attente</span>
        </div>
        <div class="flex items-center">
            <div class="w-4 h-4 bg-green-500 rounded mr-2"></div>
            <span class="text-gray-700">Terminé</span>
        </div>
        <div class="flex items-center">
            <div class="w-4 h-4 bg-red-500 rounded mr-2"></div>
            <span class="text-gray-700">Annulé</span>
        </div>
    </div>

    <!-- Calendrier -->
    <div id="calendar"></div>
</div>

<!-- Modal détails événement -->
<div id="eventModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" onclick="closeModal(event)">
    <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4 p-6" onclick="event.stopPropagation()">
        <div class="flex justify-between items-start mb-4">
            <h3 class="text-xl font-bold text-gray-900" id="modalTitle"></h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <i class="fas fa-times text-xl"></i>
            </button>
        </div>
        
        <div class="space-y-3 text-sm">
            <div class="flex items-start">
                <i class="fas fa-user-md text-blue-600 w-5 mr-3 mt-1"></i>
                <div>
                    <p class="text-gray-500 text-xs">Praticien</p>
                    <p class="text-gray-900 font-semibold" id="modalPraticien"></p>
                </div>
            </div>
            
            <div class="flex items-start">
                <i class="fas fa-stethoscope text-blue-600 w-5 mr-3 mt-1"></i>
                <div>
                    <p class="text-gray-500 text-xs">Spécialité</p>
                    <p class="text-gray-900" id="modalSpecialite"></p>
                </div>
            </div>
            
            <div class="flex items-start">
                <i class="fas fa-calendar text-blue-600 w-5 mr-3 mt-1"></i>
                <div>
                    <p class="text-gray-500 text-xs">Date et heure</p>
                    <p class="text-gray-900" id="modalDateTime"></p>
                </div>
            </div>
            
            <div class="flex items-start">
                <i class="fas fa-info-circle text-blue-600 w-5 mr-3 mt-1"></i>
                <div>
                    <p class="text-gray-500 text-xs">Statut</p>
                    <p class="text-gray-900" id="modalStatut"></p>
                </div>
            </div>
            
            <div class="flex items-start" id="modalMotifContainer">
                <i class="fas fa-file-alt text-blue-600 w-5 mr-3 mt-1"></i>
                <div>
                    <p class="text-gray-500 text-xs">Motif</p>
                    <p class="text-gray-900" id="modalMotif"></p>
                </div>
            </div>
            
            <div class="flex items-start" id="modalBureauContainer">
                <i class="fas fa-map-marker-alt text-blue-600 w-5 mr-3 mt-1"></i>
                <div>
                    <p class="text-gray-500 text-xs">Lieu</p>
                    <p class="text-gray-900" id="modalBureau"></p>
                </div>
            </div>
        </div>
        
        <div class="mt-6 flex justify-end">
            <button onclick="closeModal()" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                Fermer
            </button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script src='https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.10/locales/fr.global.min.js'></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'fr',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            buttonText: {
                today: "Aujourd'hui",
                month: 'Mois',
                week: 'Semaine',
                day: 'Jour'
            },
            events: {
                url: '{{ route("patient.calendrier.events") }}',
                failure: function() {
                    alert('Erreur lors du chargement des événements');
                }
            },
            eventClick: function(info) {
                info.jsEvent.preventDefault();
                
                const props = info.event.extendedProps;
                
                document.getElementById('modalTitle').textContent = info.event.title;
                document.getElementById('modalPraticien').textContent = props.praticien;
                document.getElementById('modalSpecialite').textContent = props.specialite;
                document.getElementById('modalDateTime').textContent = 
                    new Date(info.event.start).toLocaleString('fr-FR', {
                        weekday: 'long',
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                document.getElementById('modalStatut').textContent = props.statut;
                
                if (props.motif) {
                    document.getElementById('modalMotifContainer').classList.remove('hidden');
                    document.getElementById('modalMotif').textContent = props.motif;
                } else {
                    document.getElementById('modalMotifContainer').classList.add('hidden');
                }
                
                if (props.bureau) {
                    document.getElementById('modalBureauContainer').classList.remove('hidden');
                    document.getElementById('modalBureau').textContent = 'Bureau ' + props.bureau;
                } else {
                    document.getElementById('modalBureauContainer').classList.add('hidden');
                }
                
                document.getElementById('eventModal').classList.remove('hidden');
            },
            height: 'auto',
            aspectRatio: 1.8,
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                meridiem: false
            }
        });
        
        calendar.render();
    });
    
    function closeModal(event) {
        if (!event || event.target.id === 'eventModal') {
            document.getElementById('eventModal').classList.add('hidden');
        }
    }
</script>
@endpush
