<?php

namespace App\Http\Controllers\Praticien;

use App\Http\Controllers\Controller;
use App\Models\RendezVous;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RendezVousController extends Controller
{
    public function reschedule(Request $request, RendezVous $rendezVous)
    {
        $praticien = auth()->user()->praticien;

        if (!$praticien || $rendezVous->praticien_id !== $praticien->id) {
            abort(403, "Vous n'avez pas l'autorisation de modifier ce rendez-vous.");
        }

        if (in_array($rendezVous->statut, ['TERMINE', 'ANNULE', 'ANNULEE', 'ABSENT'])) {
            return back()->withErrors([
                'statut' => 'Impossible de reprogrammer un rendez-vous terminé ou annulé.',
            ]);
        }

        $validated = $request->validate([
            'date' => ['required', 'date', 'after_or_equal:today'],
            'heure' => ['required', 'date_format:H:i'],
            'motif' => ['nullable', 'string', 'max:500'],
        ]);

        $nouvelleDateHeure = Carbon::createFromFormat('Y-m-d H:i', $validated['date'] . ' ' . $validated['heure'], config('app.timezone'));

        if ($nouvelleDateHeure->lessThan(now())) {
            return back()->withErrors([
                'date' => 'La nouvelle date et l\'heure doivent être ultérieures à l\'heure actuelle.',
            ])->withInput();
        }

        $existeConflit = RendezVous::where('praticien_id', $praticien->id)
            ->where('id', '!=', $rendezVous->id)
            ->whereDate('date_heure_rdv', $nouvelleDateHeure->toDateString())
            ->whereTime('date_heure_rdv', $nouvelleDateHeure->format('H:i:s'))
            ->exists();

        if ($existeConflit) {
            return back()->withErrors([
                'horaire' => 'Un autre rendez-vous est déjà planifié sur ce créneau.',
            ])->withInput();
        }

        $rendezVous->date_heure_rdv = $nouvelleDateHeure;

        if (!empty($validated['motif'])) {
            $rendezVous->notes = $validated['motif'];
        }

        if (in_array($rendezVous->statut, ['CONFIRME', 'EN_COURS'])) {
            $rendezVous->statut = 'PLANIFIE';
        }

        $rendezVous->save();

        return back()->with('success', 'Le rendez-vous a été reprogrammé avec succès.');
    }
}
