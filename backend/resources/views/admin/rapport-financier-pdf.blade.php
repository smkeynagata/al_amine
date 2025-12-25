
<!DOCTYPE html>
@php use Carbon\Carbon; @endphp
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport financier</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 13px; color: #222; background: #f8fafc; }
        .header { display: flex; align-items: center; border-bottom: 2px solid #059669; margin-bottom: 24px; padding-bottom: 8px; }
        .logo { width: 48px; height: 48px; margin-right: 16px; }
        .title { font-size: 22px; font-weight: bold; color: #059669; letter-spacing: 1px; }
        .period { color: #555; font-size: 13px; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 18px; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px #e5e7eb; }
        th, td { border: 1px solid #e5e7eb; padding: 10px 12px; text-align: left; }
        th { background-color: #059669; color: #fff; text-transform: uppercase; font-size: 12px; letter-spacing: 1px; }
        tr:nth-child(even) { background: #f3f4f6; }
        .muted { color: #6b7280; font-size: 12px; margin-top: 18px; }
        .footer { margin-top: 32px; text-align: right; font-size: 11px; color: #888; }
        ul { margin: 0; padding-left: 16px; }
    </style>
</head>
<body>
    <div class="header">
        <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/Logo-hospital.png" class="logo" alt="Al-Amine RDV" />
        <div>
            <div class="title">Rapport financier</div>
            <div class="period">Période du {{ Carbon::parse($dateDebut)->format('d/m/Y') }} au {{ Carbon::parse($dateFin)->format('d/m/Y') }}</div>
        </div>
    </div>

    <table>
        <tr>
            <th>Indicateur</th>
            <th>Valeur</th>
        </tr>
        <tr>
            <td>Chiffre d'affaires total</td>
            <td>{{ number_format($data['ca_total'], 0, ',', ' ') }} FCFA</td>
        </tr>
        <tr>
            <td>Factures émises</td>
            <td>{{ $data['factures_emises'] }}</td>
        </tr>
        <tr>
            <td>Factures payées</td>
            <td>{{ $data['factures_payees'] }}</td>
        </tr>
        <tr>
            <td>Montants par méthode</td>
            <td>
                <ul>
                    @forelse($data['paiements_par_methode'] as $ligne)
                        <li>{{ $ligne->methode_paiement ?? 'Inconnue' }} : {{ number_format($ligne->total, 0, ',', ' ') }} FCFA</li>
                    @empty
                        <li>Aucun paiement enregistré</li>
                    @endforelse
                </ul>
            </td>
        </tr>
    </table>

    <div class="muted">Rapport généré automatiquement le {{ now()->format('d/m/Y à H:i') }}.</div>
    <div class="footer">Al-Amine RDV &copy; {{ date('Y') }}</div>
</body>
</html>
