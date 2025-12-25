@extends('emails.layouts.base')

@section('content')
    <p style="margin:0 0 18px;font-size:18px;font-weight:700;color:#0f172a;">
        Bonjour {{ $prenom }},
    </p>
    <p style="margin:0 0 16px;">Nous avons bien reçu votre paiement en ligne. Merci de votre confiance&nbsp;!</p>

    <div class="badge" style="background:rgba(16,185,129,0.14);color:#047857;margin-bottom:18px;">
        Paiement confirmé
    </div>

    <h2 style="margin:0 0 12px;font-size:18px;color:#0f172a;">Détails du paiement</h2>
    <div style="margin-top:16px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin:6px 0;padding:10px 14px;background:#fff;border-radius:12px;border:1px solid rgba(59,130,246,0.12);">
            <span style="color:#475569;font-weight:600;">Montant payé</span>
            <span style="color:#0f172a;font-weight:600;">{{ $montant_formate }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin:6px 0;padding:10px 14px;background:#fff;border-radius:12px;border:1px solid rgba(59,130,246,0.12);">
            <span style="color:#475569;font-weight:600;">Méthode</span>
            <span style="color:#0f172a;font-weight:600;">{{ $methode }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin:6px 0;padding:10px 14px;background:#fff;border-radius:12px;border:1px solid rgba(59,130,246,0.12);">
            <span style="color:#475569;font-weight:600;">Référence</span>
            <span style="color:#0f172a;font-weight:600;">{{ $reference }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin:6px 0;padding:10px 14px;background:#fff;border-radius:12px;border:1px solid rgba(59,130,246,0.12);">
            <span style="color:#475569;font-weight:600;">Transaction</span>
            <span style="color:#0f172a;font-weight:600;">{{ $transaction }}</span>
        </div>
    </div>

    @if($demande)
        <hr style="margin:26px 0;border:none;border-top:1px solid rgba(15,23,42,0.08);">
        <h2 style="margin:0 0 12px;font-size:18px;color:#0f172a;">Demande de rendez-vous</h2>
        <div style="margin-top:16px;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin:6px 0;padding:10px 14px;background:#fff;border-radius:12px;border:1px solid rgba(59,130,246,0.12);">
                <span style="color:#475569;font-weight:600;">Spécialité</span>
                <span style="color:#0f172a;font-weight:600;">{{ $demande->specialite?->nom ?? 'À confirmer' }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin:6px 0;padding:10px 14px;background:#fff;border-radius:12px;border:1px solid rgba(59,130,246,0.12);">
                <span style="color:#475569;font-weight:600;">Praticien</span>
                <span style="color:#0f172a;font-weight:600;">{{ $demande->praticien?->user?->nom_complet ?? 'À confirmer' }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin:6px 0;padding:10px 14px;background:#fff;border-radius:12px;border:1px solid rgba(59,130,246,0.12);">
                <span style="color:#475569;font-weight:600;">Date souhaitée</span>
                <span style="color:#0f172a;font-weight:600;">{{ optional($demande->date_heure_souhaitee)->format('d/m/Y H\hi') ?? 'À planifier' }}</span>
            </div>
        </div>
    @endif

    <p style="margin:24px 0 28px;">Notre équipe de secrétariat va maintenant valider votre demande. Vous recevrez une notification dès que le rendez-vous sera planifié.</p>

    <a class="cta-button" href="{{ $cta_url }}" target="_blank" rel="noopener">
        Consulter mes paiements
    </a>

    <p style="margin:28px 0 0;">À très bientôt,<br>Al-Amine RDV</p>
@endsection
