@extends('secretaire.layouts.app')

@section('title', 'Relances automatiques')

@section('content')
<div class="mb-6 rounded-2xl bg-gradient-to-r from-blue-600 via-blue-700 to-indigo-800 p-6 text-white shadow-xl">
    <h1 class="text-2xl font-bold">Module de relance</h1>
    <p class="mt-1 text-sm text-blue-100">Configurez vos modèles de messages et déclenchez des relances email / SMS pour les factures impayées ou les rendez-vous à confirmer.</p>
</div>

@if(session('success'))
<div class="mb-6 rounded-xl border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
    {{ session('success') }}
</div>
@endif

<div class="mb-8 rounded-2xl border border-blue-100 bg-white p-6 shadow-lg">
    <div class="mb-4 flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
        <div>
            <h2 class="flex items-center gap-2 text-lg font-semibold text-gray-800">
                <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                Modèles de messages
            </h2>
            <p class="mt-1 text-xs text-gray-500"></p>
        </div>
        <div class="flex items-center gap-4 text-sm text-gray-600">
            <label class="flex items-center gap-2">
                <input id="channel-email" type="checkbox" checked class="h-4 w-4 rounded border-blue-300 text-blue-600 focus:ring-blue-500">
                Email
            </label>
            <label class="flex items-center gap-2">
                <input id="channel-sms" type="checkbox" class="h-4 w-4 rounded border-blue-300 text-blue-600 focus:ring-blue-500">
                SMS (journalisé)
            </label>
        </div>
    </div>

    <div class="grid gap-6 md:grid-cols-2">
        @php
            $factureTemplate = $templates[\App\Models\ReminderTemplate::TYPE_FACTURE_IMPAYEE] ?? null;
            $rdvTemplate = $templates[\App\Models\ReminderTemplate::TYPE_RDV_CONFIRMATION] ?? null;
        @endphp

        @if($factureTemplate)
        <form method="POST" action="{{ route('secretaire.relances.templates.update', $factureTemplate) }}" class="space-y-4">
            @csrf
            <div class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-blue-100 text-blue-700">1</span>
                Relance facture impayée
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Objet email</label>
                <input type="text" name="email_subject" value="{{ old('email_subject', $factureTemplate->email_subject) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Corps email</label>
                <textarea name="email_body" rows="6" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">{{ old('email_body', $factureTemplate->email_body) }}</textarea>
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Message SMS</label>
                <textarea name="sms_body" rows="3" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200" >{{ old('sms_body', $factureTemplate->sms_body) }}</textarea>
            </div>
            <div class="flex items-center gap-4 text-sm text-gray-600">
                <label class="flex items-center gap-2">
                    <input type="hidden" name="channel_email" value="0">
                    <input type="checkbox" name="channel_email" value="1" class="h-4 w-4 rounded border-blue-300 text-blue-600 focus:ring-blue-500" {{ $factureTemplate->channel_email ? 'checked' : '' }}>
                    Email actif
                </label>
                <label class="flex items-center gap-2">
                    <input type="hidden" name="channel_sms" value="0">
                    <input type="checkbox" name="channel_sms" value="1" class="h-4 w-4 rounded border-blue-300 text-blue-600 focus:ring-blue-500" {{ $factureTemplate->channel_sms ? 'checked' : '' }}>
                    SMS actif
                </label>
            </div>
            <div class="text-right">
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-md transition hover:bg-blue-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Enregistrer
                </button>
            </div>
        </form>
        @endif

        @if($rdvTemplate)
        <form method="POST" action="{{ route('secretaire.relances.templates.update', $rdvTemplate) }}" class="space-y-4">
            @csrf
            <div class="flex items-center gap-2 text-sm font-semibold text-gray-700">
                <span class="inline-flex h-6 w-6 items-center justify-center rounded-lg bg-blue-100 text-blue-700">2</span>
                Rappel de rendez-vous
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Objet email</label>
                <input type="text" name="email_subject" value="{{ old('email_subject', $rdvTemplate->email_subject) }}" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Corps email</label>
                <textarea name="email_body" rows="6" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">{{ old('email_body', $rdvTemplate->email_body) }}</textarea>
            </div>
            <div>
                <label class="mb-1 block text-xs font-semibold uppercase tracking-wide text-gray-500">Message SMS</label>
                <textarea name="sms_body" rows="3" class="w-full rounded-xl border border-gray-200 px-4 py-3 text-sm shadow-sm focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">{{ old('sms_body', $rdvTemplate->sms_body) }}</textarea>
            </div>
            <div class="flex items-center gap-4 text-sm text-gray-600">
                <label class="flex items-center gap-2">
                    <input type="hidden" name="channel_email" value="0">
                    <input type="checkbox" name="channel_email" value="1" class="h-4 w-4 rounded border-blue-300 text-blue-600 focus:ring-blue-500" {{ $rdvTemplate->channel_email ? 'checked' : '' }}>
                    Email actif
                </label>
                <label class="flex items-center gap-2">
                    <input type="hidden" name="channel_sms" value="0">
                    <input type="checkbox" name="channel_sms" value="1" class="h-4 w-4 rounded border-blue-300 text-blue-600 focus:ring-blue-500" {{ $rdvTemplate->channel_sms ? 'checked' : '' }}>
                    SMS actif
                </label>
            </div>
            <div class="text-right">
                <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2 text-sm font-semibold text-white shadow-md transition hover:bg-blue-700">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Enregistrer
                </button>
            </div>
        </form>
        @endif
    </div>
</div>

<div class="grid gap-6 lg:grid-cols-2">
    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Factures impayées</h3>
            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">{{ $factures->count() }} en attente</span>
        </div>
        <div class="overflow-hidden rounded-2xl border border-blue-100 bg-white shadow-lg">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-blue-50 text-left text-xs font-semibold uppercase tracking-wide text-blue-800">
                    <tr>
                        <th class="px-4 py-3">Patient</th>
                        <th class="px-4 py-3">Montant restant</th>
                        <th class="px-4 py-3">Émise le</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($factures as $facture)
                    <tr class="hover:bg-blue-50/40">
                        <td class="px-4 py-3">
                            <div class="font-semibold text-gray-800">{{ $facture->patient->user->nom_complet }}</div>
                            <div class="text-xs text-gray-500">Facture #{{ $facture->numero_facture }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-800">{{ number_format($facture->montant_restant, 0, ',', ' ') }} FCFA</td>
                        <td class="px-4 py-3 text-xs text-gray-500">{{ optional($facture->date_facture)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3 text-right">
                            <button type="button" class="send-reminder inline-flex items-center gap-2 rounded-xl bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow transition hover:bg-blue-700" data-type="facture" data-id="{{ $facture->id }}">
                                Envoyer
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">Aucune facture impayée 🎉</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="space-y-4">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">Rendez-vous à confirmer (7 prochains jours)</h3>
            <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">{{ $rdvs->count() }} à relancer</span>
        </div>
        <div class="overflow-hidden rounded-2xl border border-blue-100 bg-white shadow-lg">
            <table class="min-w-full divide-y divide-gray-100 text-sm">
                <thead class="bg-blue-50 text-left text-xs font-semibold uppercase tracking-wide text-blue-800">
                    <tr>
                        <th class="px-4 py-3">Patient</th>
                        <th class="px-4 py-3">Praticien</th>
                        <th class="px-4 py-3">Date & heure</th>
                        <th class="px-4 py-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($rdvs as $rdv)
                    <tr class="hover:bg-blue-50/40">
                        <td class="px-4 py-3">
                            <div class="font-semibold text-gray-800">{{ $rdv->patient->user->nom_complet }}</div>
                            <div class="text-xs text-gray-500">Statut : {{ ucfirst(strtolower($rdv->statut)) }}</div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">Dr. {{ $rdv->praticien->user->nom_complet }}</td>
                        <td class="px-4 py-3 text-sm font-semibold text-gray-800">{{ $rdv->date_heure_rdv->locale('fr')->isoFormat('ddd D MMM • HH:mm') }}</td>
                        <td class="px-4 py-3 text-right">
                            <button type="button" class="send-reminder inline-flex items-center gap-2 rounded-xl bg-blue-600 px-3 py-2 text-xs font-semibold text-white shadow transition hover:bg-blue-700" data-type="rdv" data-id="{{ $rdv->id }}">
                                Envoyer
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-sm text-gray-500">Aucun rappel à envoyer.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-8 rounded-2xl border border-blue-100 bg-white p-6 shadow-lg">
    <h3 class="mb-4 text-lg font-semibold text-gray-800">Historique des relances</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-100 text-sm">
            <thead class="bg-blue-50 text-xs font-semibold uppercase tracking-wide text-blue-800">
                <tr>
                    <th class="px-4 py-3 text-left">Date</th>
                    <th class="px-4 py-3 text-left">Patient</th>
                    <th class="px-4 py-3 text-left">Canal</th>
                    <th class="px-4 py-3 text-left">Statut</th>
                    <th class="px-4 py-3 text-left">Modèle</th>
                    <th class="px-4 py-3 text-left">Message</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($logs as $log)
                <tr>
                    <td class="px-4 py-3 text-xs text-gray-500">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3 text-sm text-gray-700">{{ $log->patient->user->nom_complet ?? '—' }}</td>
                    <td class="px-4 py-3 text-xs font-semibold uppercase text-gray-600">{{ strtoupper($log->channel) }}</td>
                    <td class="px-4 py-3 text-xs font-semibold {{ $log->status === 'SENT' ? 'text-green-600' : ($log->status === 'FAILED' ? 'text-red-600' : 'text-gray-500') }}">{{ $log->status }}</td>
                    <td class="px-4 py-3 text-xs text-gray-500">{{ $log->template->label ?? '—' }}</td>
                    <td class="px-4 py-3 text-xs text-gray-500">{{ $log->error ?? '—' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-4 py-6 text-center text-sm text-gray-500">Aucune relance enregistrée pour le moment.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const buttons = document.querySelectorAll('.send-reminder');
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const channelEmail = document.getElementById('channel-email');
        const channelSms = document.getElementById('channel-sms');

        function selectedChannels() {
            const channels = [];
            if (channelEmail.checked) { channels.push('email'); }
            if (channelSms.checked) { channels.push('sms'); }
            return channels.length ? channels : ['email'];
        }

        buttons.forEach(button => {
            button.addEventListener('click', async () => {
                const type = button.dataset.type;
                const targetId = button.dataset.id;

                button.disabled = true;
                button.classList.add('opacity-70');

                try {
                    const response = await fetch('{{ route('secretaire.relances.send') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            type,
                            target_id: targetId,
                            channels: selectedChannels(),
                        }),
                    });

                    if (!response.ok) {
                        throw new Error('Erreur serveur');
                    }

                    window.alert('Relance envoyée avec succès.');
                } catch (error) {
                    console.error(error);
                    window.alert("Impossible d'envoyer la relance. Veuillez réessayer.");
                } finally {
                    button.disabled = false;
                    button.classList.remove('opacity-70');
                }
            });
        });
    });
</script>
@endpush
