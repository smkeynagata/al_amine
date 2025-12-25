<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Certificat médical</title>
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', Helvetica, Arial, sans-serif;
            color: #0f172a;
            font-size: 12px;
            line-height: 1.6;
            margin: 0;
            padding: 32px 40px;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #3B82F6;
            padding-bottom: 12px;
            margin-bottom: 24px;
        }
        .brand {
            font-size: 24px;
            font-weight: 700;
            color: #1E40AF;
            text-transform: uppercase;
        }
        .subtitle {
            font-size: 12px;
            font-weight: 500;
            color: #334155;
        }
        .section-title {
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            margin-top: 24px;
            margin-bottom: 8px;
            color: #1E293B;
        }
        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }
        .info-table td {
            padding: 6px 0;
            vertical-align: top;
        }
        .info-label {
            width: 32%;
            font-weight: 600;
            color: #1E3A8A;
            letter-spacing: 0.3px;
        }
        .body-text {
            text-align: justify;
            margin-top: 12px;
        }
        .signature {
            margin-top: 48px;
            display: flex;
            justify-content: flex-end;
        }
        .signature-block {
            text-align: right;
            border-top: 1px solid #3B82F6;
            padding-top: 8px;
            min-width: 220px;
        }
        .footer {
            margin-top: 48px;
            font-size: 10px;
            color: #64748B;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <div class="brand">Al-Amine RDV</div>
            <div class="subtitle">Centre Hospitalier Universitaire • Dakar, Sénégal</div>
        </div>
        <div style="text-align:right;">
            <div style="font-weight:600;color:#3B82F6;">Certificat médical</div>
            <div style="font-size:11px;color:#64748B;">Référence&nbsp;: {{ strtoupper(\Illuminate\Support\Str::slug($patient->nom_complet ?? 'patient', '-')) }}-{{ now()->format('YmdHis') }}</div>
        </div>
    </div>

    <div>
        <div class="section-title">Informations du patient</div>
        <table class="info-table">
            <tr>
                <td class="info-label">Nom complet</td>
                <td>{{ $patient->nom_complet ?? $patient->user->nom_complet ?? '—' }}</td>
            </tr>
            <tr>
                <td class="info-label">Date de naissance</td>
                <td>
                    @if(!empty($patient->date_naissance))
                        {{ \Illuminate\Support\Carbon::parse($patient->date_naissance)->translatedFormat('d F Y') }}
                        ({{ \App\Helpers\helpers::calculateAge($patient->date_naissance) }} ans)
                    @else
                        —
                    @endif
                </td>
            </tr>
            <tr>
                <td class="info-label">Adresse</td>
                <td>{{ $patient->adresse ?? '—' }}</td>
            </tr>
            <tr>
                <td class="info-label">Téléphone</td>
                <td>{{ isset($patient->telephone) ? \App\Helpers\helpers::formatPhone($patient->telephone) : '—' }}</td>
            </tr>
        </table>
    </div>

    <div>
        <div class="section-title">Certificat</div>
        <p class="body-text">
            Je soussigné(e) {{ auth()->user()->nom_complet ?? 'Dr. __________________' }}, médecin d'Al-Amine RDV,
            certifie avoir examiné ce jour le(la) patient(e) susnommé(e).
        </p>
        <p class="body-text">
            L'examen clinique réalisé le {{ $date->translatedFormat('d F Y') }} ne révèle aucune contre-indication médicale
            pour le motif suivant&nbsp;: <strong>{{ $motif }}</strong>.
        </p>
        <p class="body-text">
            Le présent certificat est délivré à la demande de l'intéressé(e) pour servir et valoir ce que de droit.
        </p>
    </div>

    <div class="signature">
        <div class="signature-block">
            <div>Dakar, le {{ $date->translatedFormat('d F Y') }}</div>
            <div style="margin-top:40px; font-weight:600;">Signature et cachet</div>
        </div>
    </div>

    <div class="footer">
        Al-Amine RDV • Avenue Cheikh Anta Diop • Dakar • Tél. +221 33 123 45 67 • www.al-amine-rdv.sn
    </div>
</body>
</html>
