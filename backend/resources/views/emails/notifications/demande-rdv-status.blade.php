@extends('emails.layouts.base')

@section('content')
    <p style="margin:0 0 18px;font-size:18px;font-weight:700;color:#0f172a;">
        Bonjour {{ $prenom }},
    </p>

    @if($status === 'validee')
        <div class="badge" style="background:rgba(37,99,235,0.15);color:#1d4ed8;margin-bottom:16px;">
            Rendez-vous confirmé
        </div>

        <h2 style="margin:0 0 12px;font-size:18px;color:#0f172a;">Votre rendez-vous est prêt</h2>
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
                <span style="color:#475569;font-weight:600;">Lieu</span>
                <span style="color:#0f172a;font-weight:600;">{{ $lieu }}</span>
            </div>
        </div>

        <p style="margin:24px 0 28px;">Merci d'arriver 10 minutes avant l'heure prévue et de vous munir de vos documents médicaux.</p>

        <a class="cta-button" href="{{ $cta_url }}" target="_blank" rel="noopener">
            Voir mon rendez-vous
        </a>
    @else
        <div class="badge" style="background:rgba(245,158,11,0.18);color:#b45309;margin-bottom:16px;">
            Demande refusée
        </div>

        <h2 style="margin:0 0 12px;font-size:18px;color:#b45309;">Nous sommes désolés</h2>
        <p style="margin:0 0 14px;">Votre demande n'a pas pu être validée pour ce créneau.</p>
        <div style="margin-top:16px;">
            <div style="display:flex;justify-content:space-between;align-items:center;margin:6px 0;padding:10px 14px;background:#fff;border-radius:12px;border:1px solid rgba(245,158,11,0.16);">
                <span style="color:#b45309;font-weight:600;">Praticien demandé</span>
                <span style="color:#0f172a;font-weight:600;">Dr. {{ $praticien }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;margin:6px 0;padding:10px 14px;background:#fff;border-radius:12px;border:1px solid rgba(245,158,11,0.16);">
                <span style="color:#b45309;font-weight:600;">Date souhaitée</span>
                <span style="color:#0f172a;font-weight:600;">{{ $date_formatee }}</span>
            </div>
        </div>

        <p style="margin:24px 0 28px;">N'hésitez pas à proposer un nouveau créneau ou à choisir un autre praticien.</p>

        <a class="cta-button" style="background:linear-gradient(135deg,#f97316,#ea580c);box-shadow:0 12px 30px rgba(249,115,22,0.35);" href="{{ $cta_url }}" target="_blank" rel="noopener">
            Faire une nouvelle demande
        </a>
    @endif

    <p style="margin:28px 0 0;">Merci de votre confiance envers Al-Amine RDV.</p>
@endsection
