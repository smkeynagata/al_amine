<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    public function edit()
    {
        $patient = auth()->user()->patient;
        return view('patient.profile.edit', compact('patient'));
    }

    public function updatePersonalInfo(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'prenom' => ['required', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'telephone' => ['required', 'string', 'max:20'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'date_naissance' => ['required', 'date'],
            'sexe' => ['required', 'in:M,F'],
            'adresse' => ['required', 'string', 'max:255'],
            'quartier' => ['nullable', 'string', 'max:255'],
            'ville' => ['nullable', 'string', 'max:255'],
        ]);

        unset($validated['numero_cni']);

        $user->update($validated);

        return redirect()->route('patient.dashboard')
            ->with('success', 'Informations personnelles mises à jour avec succès.');
    }

    public function updateHealthInfo(Request $request)
    {
        $patient = auth()->user()->patient;
        
        $validated = $request->validate([
            'poids' => ['nullable', 'numeric', 'min:0', 'max:500'],
            'taille' => ['nullable', 'numeric', 'min:0', 'max:300'],
            'groupe_sanguin' => ['nullable', 'in:A+,A-,B+,B-,AB+,AB-,O+,O-'],
            'allergies' => ['nullable', 'string'],
            'antecedents' => ['nullable', 'string'],
            'maladies_chroniques' => ['nullable', 'string'],
            'medicaments_actuels' => ['nullable', 'string'],
            'chirurgies_passees' => ['nullable', 'string'],
            'personne_contact_urgence' => ['nullable', 'string'],
            'fumeur' => ['boolean'],
            'consommation_alcool' => ['boolean'],
            'notes_supplementaires' => ['nullable', 'string'],
        ]);

        $patient->update($validated);

        return redirect()->route('patient.dashboard')
            ->with('success', 'Informations de santé mises à jour avec succès.');
    }

    public function updateInsuranceInfo(Request $request)
    {
        $patient = auth()->user()->patient;
        
        $validated = $request->validate([
            'numero_securite_sociale' => ['nullable', 'string', 'max:50'],
            'mutuelle' => ['nullable', 'string', 'max:255'],
            'numero_mutuelle' => ['nullable', 'string', 'max:50'],
        ]);

        $patient->update($validated);

        return redirect()->route('patient.dashboard')
            ->with('success', 'Informations d\'assurance mises à jour avec succès.');
    }

    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        $user = auth()->user();

        // Supprimer l'ancienne photo si elle existe
        if ($user->photo_profil && Storage::disk('public')->exists($user->photo_profil)) {
            Storage::disk('public')->delete($user->photo_profil);
        }

        // Stocker la nouvelle photo
        $path = $request->file('photo')->store('photos/profils', 'public');

        $user->update(['photo_profil' => $path]);

        return redirect()->route('patient.profile.edit')
            ->with('success', 'Votre photo de profil a été mise à jour avec succès.');
    }

    public function deletePhoto()
    {
        $user = auth()->user();

        if ($user->photo_profil && Storage::disk('public')->exists($user->photo_profil)) {
            Storage::disk('public')->delete($user->photo_profil);
            $user->update(['photo_profil' => null]);
        }

        return redirect()->route('patient.profile.edit')
            ->with('success', 'Votre photo de profil a été supprimée avec succès.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = auth()->user();
        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('patient.profile.edit')
            ->with('success', 'Mot de passe changé avec succès.');
    }
}
