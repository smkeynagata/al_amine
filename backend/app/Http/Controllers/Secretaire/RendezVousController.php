<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\ManagesRendezVous;
use App\Models\DemandeRdv;
use App\Models\Praticien;
use App\Models\RendezVous;
use App\Notifications\RendezVousStatusNotification;
use App\Services\PlanningService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RendezVousController extends Controller
{
    use ManagesRendezVous;

    public function showReprogrammation(Request $request, DemandeRdv $demandeRdv)
    {
        $periodeDebut = now()->startOfWeek();
        $periodeFin = $periodeDebut->copy()->addWeeks(2)->endOfDay();

        $eligiblePraticiens = Praticien::with([
                'user:id,name,prenom',
                'specialites:id',
                'disponibilites' => fn($query) => $query->where('est_active', true),
                'rendezVous' => fn($query) => $query
                    ->where('date_heure_rdv', '>=', $periodeDebut)
                    ->where('date_heure_rdv', '<=', $periodeFin),
            ])
            ->when($demandeRdv->specialite_id, fn($query) => $query
                ->whereHas('specialites', fn($sub) => $sub->where('specialite_id', $demandeRdv->specialite_id)))
            ->get();

        $selectedPraticien = $eligiblePraticiens->firstWhere('id', $request->integer('praticien_id'))
            ?? ($demandeRdv->praticien_id ? $eligiblePraticiens->firstWhere('id', $demandeRdv->praticien_id) : null)
            ?? $eligiblePraticiens->first();

        $planning = $selectedPraticien
            ? PlanningService::availability($selectedPraticien, $periodeDebut, $periodeFin, $this->slotDuration, 200)
            : ['slots' => collect(), 'rdvs' => collect()];

        $slotsCollection = collect($planning['slots'] ?? []);
        $rdvsCollection = collect($planning['rdvs'] ?? []);

        $slotsByDay = $slotsCollection
            ->groupBy(fn($slot) => substr($slot['value'], 0, 10))
            ->map(function ($slots, $date) {
                $carb = Carbon::createFromFormat('Y-m-d', $date);

                return [
                    'date' => $date,
                    'label' => ucfirst($carb->locale('fr')->isoFormat('dddd D MMMM')),
                    'slots' => $slots->values(),
                ];
            })
            ->sortKeys()
            ->values();

        $busyByDay = $rdvsCollection
            ->groupBy(fn($rdv) => substr($rdv['start'], 0, 10))
            ->map(function ($rdvs, $date) {
                $carb = Carbon::createFromFormat('Y-m-d', $date);

                return [
                    'date' => $date,
                    'label' => ucfirst($carb->locale('fr')->isoFormat('dddd D MMMM')),
                    'rdvs' => $rdvs->map(function ($rdv) use ($carb) {
                        $start = Carbon::createFromFormat('Y-m-d\TH:i', $rdv['start']);
                        $end = Carbon::createFromFormat('Y-m-d\TH:i', $rdv['end']);

                        return [
                            'start' => $rdv['start'],
                            'end' => $rdv['end'],
                            'label' => $start->format('H:i') . ' - ' . $end->format('H:i'),
                        ];
                    })->values(),
                ];
            })
            ->sortKeys()
            ->values();

        $existingRdv = $demandeRdv->rendezVous;

        return view('secretaire.reprogrammation', [
            'demande' => $demandeRdv,
            'praticiens' => $eligiblePraticiens,
            'selectedPraticien' => $selectedPraticien,
            'slotsByDay' => $slotsByDay,
            'busyByDay' => $busyByDay,
            'periodeDebut' => $periodeDebut,
            'periodeFin' => $periodeFin,
            'existingRdv' => $existingRdv,
            'workHours' => [
                'start' => PlanningService::DEFAULT_START_HOUR,
                'end' => PlanningService::DEFAULT_END_HOUR,
            ],
        ]);
    }

    public function storeReprogrammation(Request $request, DemandeRdv $demandeRdv)
    {
        $validated = $request->validate([
            'praticien_id' => 'required|exists:praticiens,id',
            'date_heure_rdv' => 'required|date_format:Y-m-d\TH:i|after:now',
            'duree' => 'required|integer|min:15|max:120',
        ]);

        $praticien = Praticien::with(['specialites:id', 'disponibilites' => fn($query) => $query->where('est_active', true)])
            ->findOrFail($validated['praticien_id']);

        if ($demandeRdv->specialite_id && !$praticien->specialites->pluck('id')->contains($demandeRdv->specialite_id)) {
            return back()->withErrors([
                'praticien_id' => 'Ce praticien ne correspond pas à la spécialité demandée.',
            ])->withInput();
        }

        $dateRdv = Carbon::createFromFormat('Y-m-d\TH:i', $validated['date_heure_rdv'], config('app.timezone'));
        $duree = (int) $validated['duree'];

        $existingRdv = $demandeRdv->rendezVous;
        $ignoreId = $existingRdv?->id;

        if ($this->hasConflict($praticien, $dateRdv, $duree, $ignoreId)) {
            return back()->withErrors([
                'date_heure_rdv' => 'Ce créneau est déjà occupé par un autre rendez-vous.',
            ])->withInput();
        }

        if ($existingRdv) {
            $existingRdv->update([
                'praticien_id' => $praticien->id,
                'date_heure_rdv' => $dateRdv,
                'duree' => $duree,
            ]);
            $rendezVous = $existingRdv;
        } else {
            $rendezVous = RendezVous::create([
                'patient_id' => $demandeRdv->patient_id,
                'praticien_id' => $praticien->id,
                'demande_rdv_id' => $demandeRdv->id,
                'date_heure_rdv' => $dateRdv,
                'duree' => $duree,
                'statut' => 'CONFIRME',
                'valide_par' => auth()->user()->secretaire->id,
            ]);
        }

        $demandeRdv->update([
            'statut' => 'CONFIRMEE',
            'praticien_id' => $praticien->id,
            'traite_par' => auth()->user()->secretaire->id,
            'date_traitement' => now(),
        ]);

        // Envoyer une notification au patient
        $demandeRdv->patient->user->notify(new \App\Notifications\DemandeRdvStatusNotification($demandeRdv, 'validee'));

        return redirect()
            ->route('secretaire.file-attente')
            ->with('success', 'Rendez-vous reprogrammé avec succès pour ' . $rendezVous->date_heure_rdv->locale('fr')->isoFormat('dddd D MMMM à HH:mm'));
    }

    public function confirm(Request $request, RendezVous $rendezVous)
    {
        if (in_array($rendezVous->statut, ['TERMINE', 'ANNULE', 'ABSENT'])) {
            return back()->withErrors([
                'rendez_vous' => 'Impossible de confirmer un rendez-vous terminé ou annulé.',
            ]);
        }

        $secretaireId = optional(auth()->user()->secretaire)->id;

        $rendezVous->update([
            'statut' => 'CONFIRME',
            'valide_par' => $secretaireId,
        ]);

        $rendezVous->loadMissing(['patient.user', 'praticien.user']);
        optional($rendezVous->patient?->user)
            ->notify(new RendezVousStatusNotification($rendezVous, 'CONFIRME'));

        return back()->with('success', 'Le rendez-vous a été confirmé.');
    }

    public function cancel(Request $request, RendezVous $rendezVous)
    {
        if (in_array($rendezVous->statut, ['TERMINE', 'ANNULE'])) {
            return back()->withErrors([
                'rendez_vous' => 'Ce rendez-vous est déjà terminé ou annulé.',
            ]);
        }

        $validated = $request->validate([
            'motif' => 'nullable|string|max:255',
        ]);

        $secretaireId = optional(auth()->user()->secretaire)->id;
        $motif = trim($validated['motif'] ?? '');

        $rendezVous->update([
            'statut' => 'ANNULE',
            'valide_par' => $secretaireId,
            'notes' => $motif !== '' ? $motif : $rendezVous->notes,
        ]);

        $rendezVous->loadMissing(['patient.user', 'praticien.user']);
        optional($rendezVous->patient?->user)
            ->notify(new RendezVousStatusNotification($rendezVous, 'ANNULE'));

        return back()->with('success', 'Le rendez-vous a été annulé.');
    }
}
