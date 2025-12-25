<?php

namespace App\Http\Controllers\Praticien;

use App\Http\Controllers\Controller;
use App\Models\Disponibilite;
use Illuminate\Http\Request;

class DisponibiliteController extends Controller
{
    public function index()
    {
        $praticien = auth()->user()->praticien;

        $disponibilites = Disponibilite::where('praticien_id', $praticien->id)
            ->orderBy('jour_semaine')
            ->orderBy('heure_debut')
            ->get();

        return view('praticien.disponibilites', compact('disponibilites'));
    }

    public function store(Request $request)
    {
        $praticien = auth()->user()->praticien;

        $request->validate([
            'jour_semaine' => 'required|in:LUNDI,MARDI,MERCREDI,JEUDI,VENDREDI,SAMEDI,DIMANCHE',
            'type_creneau' => 'required|in:MATIN,APRES_MIDI,JOURNEE',
            'heure_debut' => 'required|date_format:H:i',
            'heure_fin' => 'required|date_format:H:i|after:heure_debut',
        ]);

        Disponibilite::create([
            'praticien_id' => $praticien->id,
            'jour_semaine' => $request->jour_semaine,
            'type_creneau' => $request->type_creneau,
            'heure_debut' => $request->heure_debut,
            'heure_fin' => $request->heure_fin,
            'est_active' => true,
        ]);

        return redirect()->route('praticien.disponibilites')
            ->with('success', 'Disponibilité ajoutée avec succès.');
    }
}
