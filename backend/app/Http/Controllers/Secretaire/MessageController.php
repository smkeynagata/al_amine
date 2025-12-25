<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Models\Praticien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index()
    {
        $secretaire = Auth::user()->secretaire;
        
        // Récupérer tous les praticiens
        $praticiens = Praticien::with('user')->get();
        
        // Récupérer les messages envoyés par le secrétaire
        $messages = Message::where('expediteur_id', Auth::id())
            ->with(['destinataire'])
            ->latest()
            ->paginate(20);
        
        return view('secretaire.messages.index', compact('praticiens', 'messages'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'praticien_id' => 'required|exists:praticiens,id',
            'contenu' => 'required|string|max:1000',
        ]);
        
        $praticien = Praticien::findOrFail($request->praticien_id);
        
        Message::create([
            'expediteur_id' => Auth::id(),
            'destinataire_id' => $praticien->user_id,
            'contenu' => $request->contenu,
            'lu' => false,
        ]);
        
        return redirect()->route('secretaire.messages')->with('success', 'Message envoyé avec succès au Dr. ' . $praticien->user->nom_complet);
    }
}
