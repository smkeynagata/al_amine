<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de Paiement - {{ $paiement->reference }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 15px;
        }
        .header h1 {
            color: #2563eb;
            font-size: 24px;
            margin-bottom: 5px;
        }
        .header p {
            color: #666;
            font-size: 11px;
        }
        .recu-badge {
            background-color: #10b981;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            display: inline-block;
            font-weight: bold;
            font-size: 14px;
            margin: 20px 0;
        }
        .info-section {
            margin-bottom: 25px;
        }
        .info-section h3 {
            background-color: #f3f4f6;
            padding: 8px 10px;
            border-left: 4px solid #2563eb;
            margin-bottom: 10px;
            font-size: 13px;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        .info-label {
            display: table-cell;
            width: 35%;
            font-weight: bold;
            color: #4b5563;
            padding: 5px 0;
        }
        .info-value {
            display: table-cell;
            color: #1f2937;
            padding: 5px 0;
        }
        .montant-box {
            background-color: #dcfce7;
            border: 2px solid #10b981;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin: 25px 0;
        }
        .montant-box .label {
            font-size: 11px;
            color: #065f46;
            margin-bottom: 5px;
        }
        .montant-box .montant {
            font-size: 28px;
            font-weight: bold;
            color: #047857;
        }
        .footer {
            margin-top: 40px;
            text-align: center;
            padding-top: 20px;
            border-top: 2px solid #e5e7eb;
        }
        .footer p {
            font-size: 10px;
            color: #6b7280;
            margin-bottom: 5px;
        }
        .signature-box {
            margin-top: 50px;
            text-align: right;
        }
        .signature-line {
            display: inline-block;
            width: 200px;
            border-top: 2px solid #333;
            margin-top: 40px;
        }
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 80px;
            color: rgba(37, 99, 235, 0.05);
            z-index: -1;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="watermark">PAYÉ</div>

    <div class="header">
        <h1>🏥 CLINIQUE AL-AMINE RDV</h1>
        <p>Adresse de la clinique • Téléphone: +221 XX XXX XX XX</p>
        <p>Email: contact@al-amine.sn • www.al-amine.sn</p>
    </div>

    <div style="text-align: center;">
        <div class="recu-badge">✓ REÇU DE PAIEMENT</div>
        <p style="font-size: 11px; color: #666; margin-top: 10px;">
            Référence: <strong>{{ $paiement->reference }}</strong>
        </p>
        <p style="font-size: 11px; color: #666;">
            Date d'émission: <strong>{{ now()->format('d/m/Y à H:i') }}</strong>
        </p>
    </div>

    <div class="info-section">
        <h3>📋 INFORMATIONS DU PATIENT</h3>
        <div class="info-row">
            <span class="info-label">Nom complet:</span>
            <span class="info-value">{{ $paiement->patient->user->nom_complet }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">N° Dossier:</span>
            <span class="info-value">{{ str_pad($paiement->patient->id, 6, '0', STR_PAD_LEFT) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Email:</span>
            <span class="info-value">{{ $paiement->patient->user->email }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Téléphone:</span>
            <span class="info-value">{{ $paiement->patient->user->telephone ?? 'N/A' }}</span>
        </div>
    </div>

    <div class="info-section">
        <h3>👨‍⚕️ INFORMATIONS DE LA CONSULTATION</h3>
        @if($paiement->facture && $paiement->facture->consultation)
            <div class="info-row">
                <span class="info-label">Praticien:</span>
                <span class="info-value">Dr. {{ $paiement->facture->consultation->praticien->user->nom_complet }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Date consultation:</span>
                <span class="info-value">{{ $paiement->facture->consultation->created_at->format('d/m/Y') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Diagnostic:</span>
                <span class="info-value">{{ Str::limit($paiement->facture->consultation->diagnostic, 80) }}</span>
            </div>
        @else
            <div class="info-row">
                <span class="info-value" style="font-style: italic; color: #6b7280;">Informations non disponibles</span>
            </div>
        @endif
    </div>

    <div class="info-section">
        <h3>💳 DÉTAILS DU PAIEMENT</h3>
        <div class="info-row">
            <span class="info-label">Date de paiement:</span>
            <span class="info-value">{{ $paiement->date_paiement->format('d/m/Y à H:i') }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Méthode:</span>
            <span class="info-value">{{ str_replace('_', ' ', $paiement->methode_paiement) }}</span>
        </div>
        @if($paiement->numero_transaction)
            <div class="info-row">
                <span class="info-label">N° Transaction:</span>
                <span class="info-value">{{ $paiement->numero_transaction }}</span>
            </div>
        @endif
        <div class="info-row">
            <span class="info-label">Statut:</span>
            <span class="info-value" style="color: #10b981; font-weight: bold;">{{ $paiement->statut }}</span>
        </div>
    </div>

    <div class="montant-box">
        <div class="label">MONTANT PAYÉ</div>
        <div class="montant">{{ number_format($paiement->montant, 0, ',', ' ') }} FCFA</div>
        <div class="label" style="margin-top: 5px; font-size: 10px;">
            ({{ ucfirst(\App\Helpers\NumberToWords::convert($paiement->montant)) }} francs CFA)
        </div>
    </div>

    @if($paiement->facture)
        <div class="info-section">
            <h3>📄 INFORMATIONS DE LA FACTURE</h3>
            <div class="info-row">
                <span class="info-label">N° Facture:</span>
                <span class="info-value">{{ $paiement->facture->numero_facture }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Montant total:</span>
                <span class="info-value">{{ number_format($paiement->facture->montant_total, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="info-row">
                <span class="info-label">Montant payé:</span>
                <span class="info-value">{{ number_format($paiement->facture->montant_paye, 0, ',', ' ') }} FCFA</span>
            </div>
            <div class="info-row">
                <span class="info-label">Montant restant:</span>
                <span class="info-value">{{ number_format($paiement->facture->montant_restant, 0, ',', ' ') }} FCFA</span>
            </div>
        </div>
    @endif

    <div class="signature-box">
        <p style="font-size: 11px; margin-bottom: 5px;">Signature et cachet</p>
        <div class="signature-line"></div>
    </div>

    <div class="footer">
        <p><strong>Ce reçu fait foi de paiement</strong></p>
        <p>Document généré électroniquement le {{ now()->format('d/m/Y à H:i') }}</p>
        <p style="margin-top: 10px;">Clinique Al-Amine RDV • Tous droits réservés © {{ now()->year }}</p>
    </div>
</body>
</html>
