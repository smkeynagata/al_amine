@extends('emails.layouts.base')

@section('content')
    <p style="margin:0 0 18px;font-size:18px;font-weight:700;color:#0f172a;">
        Bonjour {{ $prenom }},
    </p>

    <div class="badge" style="background:rgba(245,158,11,0.18);color:#b45309;margin-bottom:16px;">
        Facture impayée
    </div>

    <h2 style="margin:0 0 12px;font-size:18px;color:#b45309;">Action requise</h2>
    <p style="margin:0 0 16px;">Nous vous rappelons qu'une facture reste en attente de paiement. Merci de la régler dans les meilleurs délais.</p>

    <div style="margin-top:16px;">
        <div style="display:flex;justify-content:space-between;align-items:center;margin:6px 0;padding:10px 14px;background:#fff;border-radius:12px;border:1px solid rgba(248,113,113,0.16);">
            <span style="color:#b45309;font-weight:600;">Numéro de facture</span>
            <span style="color:#0f172a;font-weight:600;">#{{ $numero_facture }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin:6px 0;padding:10px 14px;background:#fff;border-radius:12px;border:1px solid rgba(248,113,113,0.16);">
            <span style="color:#b45309;font-weight:600;">Date d'émission</span>
            <span style="color:#0f172a;font-weight:600;">{{ $date_facture }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin:6px 0;padding:10px 14px;background:#fff;border-radius:12px;border:1px solid rgba(248,113,113,0.16);">
            <span style="color:#b45309;font-weight:600;">Montant total</span>
            <span style="color:#0f172a;font-weight:600;">{{ $montant_total }}</span>
        </div>
        <div style="display:flex;justify-content:space-between;align-items:center;margin:6px 0;padding:10px 14px;background:#fff;border-radius:12px;border:1px solid rgba(248,113,113,0.16);">
            <span style="color:#b45309;font-weight:600;">Montant restant</span>
            <span style="color:#0f172a;font-weight:600;">{{ $montant_restant }}</span>
        </div>
    </div>

    <p style="margin:24px 0 28px;">Vous pouvez effectuer le paiement en ligne ou vous rendre à l'accueil de l'hôpital.</p>

    <a class="cta-button" href="{{ $cta_url }}" target="_blank" rel="noopener">
        Payer maintenant
    </a>

    <p style="margin:28px 0 0;">Merci de votre confiance envers Al-Amine RDV.</p>
@endsection
