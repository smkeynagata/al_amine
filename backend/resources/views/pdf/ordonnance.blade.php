<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ordonnance #{{ $ordonnance->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            padding: 40px;
        }
        .header {
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 10px;
        }
        .praticien-info {
            margin-top: 10px;
        }
        .praticien-info p {
            margin: 3px 0;
        }
        .title {
            text-align: center;
            font-size: 20px;
            font-weight: bold;
            margin: 30px 0;
            color: #1e40af;
        }
        .patient-info {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        .patient-info h3 {
            font-size: 14px;
            margin-bottom: 10px;
            color: #1f2937;
        }
        .info-row {
            display: flex;
            margin-bottom: 5px;
        }
        .info-label {
            font-weight: bold;
            width: 150px;
        }
        .medicaments {
            margin: 30px 0;
        }
        .medicaments h3 {
            font-size: 16px;
            margin-bottom: 15px;
            color: #1f2937;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 5px;
        }
        .medicament {
            background-color: #fef3c7;
            padding: 15px;
            margin-bottom: 15px;
            border-left: 4px solid #f59e0b;
            border-radius: 4px;
        }
        .medicament-name {
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .posologie {
            font-style: italic;
            color: #4b5563;
        }
        .instructions {
            margin: 30px 0;
            padding: 15px;
            background-color: #dbeafe;
            border-left: 4px solid #3b82f6;
            border-radius: 4px;
        }
        .instructions h3 {
            font-size: 14px;
            margin-bottom: 10px;
            color: #1e40af;
        }
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        .signature {
            text-align: right;
            margin-top: 40px;
        }
        .signature p {
            margin: 5px 0;
        }
        .date {
            text-align: right;
            font-style: italic;
            color: #6b7280;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo">AL-AMINE RDV</div>
        <div class="praticien-info">
            <p><strong>Dr. {{ $ordonnance->praticien->user->nom_complet }}</strong></p>
            <p>{{ $ordonnance->praticien->specialites->pluck('nom')->implode(', ') }}</p>
            <p>Tél: {{ $ordonnance->praticien->user->telephone }}</p>
            <p>Email: {{ $ordonnance->praticien->user->email }}</p>
        </div>
    </div>

    <div class="title">ORDONNANCE MÉDICALE</div>

    <div class="date">
        {{ $ordonnance->created_at->locale('fr')->isoFormat('LL') }}
    </div>

    <div class="patient-info">
        <h3>INFORMATIONS PATIENT</h3>
        <div class="info-row">
            <span class="info-label">Nom complet:</span>
            <span>{{ $ordonnance->patient->user->nom_complet }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Date de naissance:</span>
            <span>{{ $ordonnance->patient->user->date_naissance->format('d/m/Y') }} ({{ $ordonnance->patient->user->age }} ans)</span>
        </div>
        <div class="info-row">
            <span class="info-label">Sexe:</span>
            <span>{{ $ordonnance->patient->user->sexe === 'M' ? 'Masculin' : 'Féminin' }}</span>
        </div>
        @if($ordonnance->patient->numero_securite_sociale)
        <div class="info-row">
            <span class="info-label">N° Sécurité Sociale:</span>
            <span>{{ $ordonnance->patient->numero_securite_sociale }}</span>
        </div>
        @endif
    </div>

    <div class="medicaments">
        <h3>PRESCRIPTION</h3>
        @if($ordonnance->medicaments)
            {!! nl2br(e($ordonnance->medicaments)) !!}
        @endif
    </div>

    @if($ordonnance->instructions)
    <div class="instructions">
        <h3>INSTRUCTIONS</h3>
        <p>{{ $ordonnance->instructions }}</p>
    </div>
    @endif

    <div class="footer">
        @if($ordonnance->duree_traitement)
        <p><strong>Durée du traitement:</strong> {{ $ordonnance->duree_traitement }}</p>
        @endif
        
        <div class="signature">
            <p>Signature et cachet du praticien</p>
            <br><br>
            <p>____________________________</p>
            <p>Dr. {{ $ordonnance->praticien->user->nom_complet }}</p>
        </div>
    </div>
</body>
</html>
