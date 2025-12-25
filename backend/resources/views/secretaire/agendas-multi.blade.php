@extends('secretaire.layouts.app')

@section('title', 'Planning multi-praticiens')

@section('content')
<div class="mb-6 rounded-2xl bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-800 p-6 text-white shadow-xl">
    <h1 class="text-2xl font-bold">Planning global des praticiens</h1>
    <p class="mt-1 text-sm text-blue-100">Visualisez en un coup d'œil l'ensemble des rendez-vous et glissez-déposez pour replanifier rapidement.</p>
</div>

<div class="grid gap-6 lg:grid-cols-4">
    <div class="space-y-6 lg:col-span-1">
        <div class="rounded-2xl border border-blue-100 bg-white p-5 shadow-lg">
            <h2 class="mb-3 flex items-center gap-2 text-lg font-semibold text-gray-800">
                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6h13M9 6L5 9m0 6l4 3" />
                </svg>
                Praticiens visibles
            </h2>
            <p class="mb-4 text-xs text-gray-500">Activez ou masquez un praticien pour filtrer le planning. Chaque couleur correspond à un praticien.</p>
            <div class="space-y-2" id="praticien-filters"></div>
        </div>

        <div class="rounded-2xl border border-blue-100 bg-white p-5 shadow-lg">
            <h2 class="mb-3 flex items-center gap-2 text-lg font-semibold text-gray-800">
                <svg class="h-5 w-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M12 8v.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Raccourcis
            </h2>
            <ul class="space-y-2 text-sm text-gray-600">
                <li>• Glissez un rendez-vous pour changer de créneau.</li>
                <li>• Étirez le rendez-vous pour ajuster la durée.</li>
                <li>• En cas de conflit, le déplacement est automatiquement annulé.</li>
            </ul>
        </div>
    </div>

    <div class="lg:col-span-3">
        <div class="rounded-2xl border border-blue-100 bg-white p-5 shadow-lg">
            <div class="mb-4 flex flex-col gap-3 md:flex-row md:items-center md:justify-between">
                <div class="flex items-center gap-2 text-sm text-gray-500">
                    <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Semaine glissante sur 7 jours • Créneaux éditables
                </div>
                <button id="refresh-button" class="flex items-center gap-2 rounded-lg border border-blue-200 px-4 py-2 text-sm font-semibold text-blue-700 transition hover:bg-blue-50">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v6h6M20 20v-6h-6M5 19A9 9 0 0119 5" />
                    </svg>
                    Actualiser
                </button>
            </div>
            <div id="calendar" class="h-[720px]"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/interaction.global.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const calendarElement = document.getElementById('calendar');
        const filtersContainer = document.getElementById('praticien-filters');
        const refreshButton = document.getElementById('refresh-button');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const praticiens = {!! json_encode($praticiens->map(function($praticien) {
            return [
                'id' => $praticien->id,
                'nom' => $praticien->user->nom_complet ?? '',
                'color' => $praticien->color_code ?? '#3b82f6',
            ];
        })->values()->toArray()) !!};
        let selectedPraticiens = praticiens.map(praticien => praticien.id);

        function renderFilters() {
            filtersContainer.innerHTML = '';
            praticiens.forEach(({ id, nom, color }) => {
                const wrapper = document.createElement('div');
                wrapper.className = 'flex items-center gap-3 rounded-xl border border-blue-100 p-3 hover:bg-blue-50';

                const input = document.createElement('input');
                input.type = 'checkbox';
                input.checked = selectedPraticiens.includes(id);
                input.className = 'h-4 w-4 rounded border-blue-300 text-blue-600 focus:ring-blue-500';
                input.addEventListener('change', () => {
                    if (input.checked) {
                        selectedPraticiens.push(id);
                    } else {
                        selectedPraticiens = selectedPraticiens.filter(value => value !== id);
                    }
                    calendar.refetchEvents();
                });

                const badge = document.createElement('span');
                badge.className = 'flex h-3 w-3 rounded-full';
                badge.style.backgroundColor = color;

                const label = document.createElement('label');
                label.className = 'flex-1 text-sm font-medium text-gray-700';
                label.textContent = nom;

                wrapper.appendChild(input);
                wrapper.appendChild(badge);
                wrapper.appendChild(label);
                filtersContainer.appendChild(wrapper);
            });
        }

        async function updateRendezVous(info) {
            const payload = {
                rendezvous_id: info.event.id,
                praticien_id: info.event.extendedProps.praticien_id,
                start: info.event.start.toISOString(),
                end: info.event.end ? info.event.end.toISOString() : null,
            };

            try {
                const response = await fetch('{{ route('secretaire.agendas.replanifier') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify(payload),
                });

                if (response.status === 409) {
                    const data = await response.json();
                    window.alert(data.message || 'Conflit détecté, opération annulée.');
                    info.revert();
                    return;
                }

                if (!response.ok) {
                    throw new Error('Erreur serveur');
                }

                const data = await response.json();
                if (data.status !== 'ok') {
                    throw new Error('Réponse inattendue');
                }
            } catch (error) {
                console.error(error);
                window.alert("Impossible d'enregistrer la replanification. Veuillez réessayer.");
                info.revert();
            }
        }

        const calendar = new FullCalendar.Calendar(calendarElement, {
            initialView: 'timeGridWeek',
            locale: 'fr',
            nowIndicator: true,
            editable: true,
            droppable: false,
            selectable: false,
            snapDuration: '00:15:00',
            slotDuration: '00:30:00',
            slotMinTime: '07:00:00',
            slotMaxTime: '21:00:00',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'timeGridDay,timeGridWeek,dayGridMonth'
            },
            eventTimeFormat: { hour: '2-digit', minute: '2-digit', hour12: false },
            events: (info, successCallback, failureCallback) => {
                const params = new URLSearchParams();
                params.append('start', info.startStr);
                params.append('end', info.endStr);
                selectedPraticiens.forEach(id => params.append('praticiens[]', id));

                fetch(`{{ route('secretaire.agendas.events') }}?${params.toString()}`)
                    .then(response => response.json())
                    .then(successCallback)
                    .catch(error => {
                        console.error(error);
                        failureCallback(error);
                    });
            },
            eventDrop: updateRendezVous,
            eventResize: updateRendezVous,
        });

        renderFilters();
        calendar.render();

        refreshButton.addEventListener('click', () => {
            calendar.refetchEvents();
        });
    });
</script>
@endpush
