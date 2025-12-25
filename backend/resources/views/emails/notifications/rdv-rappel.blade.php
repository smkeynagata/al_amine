@extends('emails.layouts.base')

@section('content')
    <p style="margin:0 0 18px;font-size:18px;font-weight:700;color:#0f172a;">
        Bonjour {{ $prenom }},
    </p>

    <div class="badge" style="background:rgba(59,130,246,0.18);color:#1d4ed8;margin-bottom:16px;">
        Rappel de rendez-vous
    </div>

    <h2 style="margin:0 0 12px;font-size:18px;color:#0f172a;">Votre rendez-vous approche</h2>
    <div style="margin-top:16px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin:6px 0;padding:10px 14px;background:#fff;border-radius:12px;border:1px solid rgba(59,130,246,0.12);">
            <span style="color:#475569;font-weight:600;">Praticien</span>
            <span style="color:#0f172a;font-weight:600;">Dr. {{ $praticien }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin:6px 0;padding:10px 14px;background:#fff;border-radius:12px;border:1px solid rgba(59,130,246,0.12);">
            <span style="color:#475569;font-weight:600;">Date</span>
            <span style="color:#0f172a;font-weight:600;">{{ $date_formatee }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin:6px 0;padding:10px 14px;background:#fff;border-radius:12px;border:1px solid rgba(59,130,246,0.12);">
            <span style="color:#475569;font-weight:600;">Heure</span>
            <span style="color:#0f172a;font-weight:600;">{{ $heure_formatee }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin:6px 0;padding:10px 14px;background:#fff;border-radius:12px;border:1px solid rgba(59,130,246,0.12);">
            <span style="color:#475569;font-weight:600;">Durée estimée</span>
            <span style="color:#0f172a;font-weight:600;">{{ $duree }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin:6px 0;padding:10px 14px;background:#fff;border-radius:12px;border:1px solid rgba(59,130,246,0.12);">
            <span style="color:#475569;font-weight:600;">Lieu</span>
            <span style="color:#0f172a;font-weight:600;">{{ $lieu }}</span>
        </div>
    </div>

    <p style="margin:24px 0 28px;">Merci d'arriver quelques minutes en avance pour faciliter votre accueil.</p>

    <a class="cta-button" href="{{ $cta_url }}" target="_blank" rel="noopener">
        Voir mon rendez-vous
    </a>

    <p style="margin:28px 0 0;">À très bientôt,<br>Al-Amine RDV</p>
@endsection
