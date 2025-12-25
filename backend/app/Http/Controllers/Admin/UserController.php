<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient;
use App\Models\Praticien;
use App\Models\Secretaire;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Filtre par recherche (nom, prénom, email)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'ILIKE', "%{$search}%")
                  ->orWhere('prenom', 'ILIKE', "%{$search}%")
                  ->orWhere('email', 'ILIKE', "%{$search}%")
                  ->orWhere('telephone', 'ILIKE', "%{$search}%");
            });
        }

        // Filtre par rôle
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut_compte', $request->statut);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20)->withQueryString();

        $metrics = [
            'total' => User::count(),
            'actifs' => User::where('statut_compte', 'ACTIF')->count(),
            'admins' => User::where('role', 'ADMIN')->count(),
            'recents' => User::where('created_at', '>=', now()->startOfMonth())->count(),
        ];

        return view('admin.users.index', compact('users', 'metrics'));
    }

    public function create()
    {
        $services = Service::all();
        $quartiers = quartiersDatekar();

        return view('admin.users.create', compact('services', 'quartiers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|in:PATIENT,PRATICIEN,SECRETAIRE,ADMIN',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:M,F',
            'numero_cni' => 'required|string|size:13|unique:users',
            'telephone' => 'required|string',
            'adresse' => 'nullable|string',
            'quartier' => 'nullable|string',
            'ville' => 'required|string',
        ]);

        if ($request->role === 'PRATICIEN') {
            $request->validate([
                'service_id' => 'required|exists:services,id',
                // 'numero_ordre' supprimé
                'tarif_consultation' => 'required|numeric|min:0',
                'annees_experience' => 'nullable|integer|min:0',
            ]);
        }

        // Créer l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'date_naissance' => $request->date_naissance,
            'sexe' => $request->sexe,
            'numero_cni' => $request->numero_cni,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
            'quartier' => $request->quartier,
            'ville' => $request->ville,
            'statut_compte' => 'ACTIF',
        ]);

        // Créer le profil spécifique selon le rôle
        switch ($request->role) {
            case 'PATIENT':
                Patient::create([
                    'user_id' => $user->id,
                    'numero_securite_sociale' => $request->numero_securite_sociale,
                    'allergies' => $request->allergies,
                    'antecedents' => $request->antecedents,
                    'mutuelle' => $request->mutuelle,
                ]);
                break;

            case 'PRATICIEN':
                Praticien::create([
                    'user_id' => $user->id,
                    'service_id' => $request->service_id,
                    // 'numero_ordre' supprimé
                    'tarif_consultation' => $request->tarif_consultation,
                    'annees_experience' => $request->annees_experience ?? 0,
                    'biographie' => $request->biographie,
                ]);
                break;

            case 'SECRETAIRE':
                Secretaire::create([
                    'user_id' => $user->id,
                    'matricule' => $request->matricule ?? 'SEC-' . strtoupper(uniqid()),
                ]);
                break;
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function show(User $user)
    {
        $user->load(['patient', 'praticien.service', 'praticien.specialites', 'secretaire']);

        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $services = Service::all();
        $quartiers = quartiersDatekar();

        return view('admin.users.edit', compact('user', 'services', 'quartiers'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'numero_cni' => 'required|string|size:13|unique:users,numero_cni,' . $user->id,
            'telephone' => 'required|string',
            'statut_compte' => 'required|in:ACTIF,SUSPENDU,DESACTIVE',
        ]);

        $user->update($request->only([
            'name', 'prenom', 'email', 'date_naissance', 'sexe',
            'numero_cni', 'telephone', 'adresse', 'quartier', 'ville', 'statut_compte'
        ]));

        // Si mot de passe fourni, le mettre à jour
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user)
    {
        // Ne pas permettre la suppression de son propre compte
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }
}

