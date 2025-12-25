
<!DOCTYPE html>
@php use Carbon\Carbon; @endphp
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport d'activité</title>
    <style>
        body { font-family: DejaVu Sans, Arial, sans-serif; font-size: 13px; color: #222; background: #f8fafc; }
        .header { display: flex; align-items: center; border-bottom: 2px solid #2563eb; margin-bottom: 24px; padding-bottom: 8px; }
        .logo { width: 48px; height: 48px; margin-right: 16px; }
        .title { font-size: 22px; font-weight: bold; color: #2563eb; letter-spacing: 1px; }
        .period { color: #555; font-size: 13px; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 18px; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px #e5e7eb; }
        th, td { border: 1px solid #e5e7eb; padding: 10px 12px; text-align: left; }
        th { background-color: #2563eb; color: #fff; text-transform: uppercase; font-size: 12px; letter-spacing: 1px; }
        tr:nth-child(even) { background: #f3f4f6; }
        .muted { color: #6b7280; font-size: 12px; margin-top: 18px; }
        .footer { margin-top: 32px; text-align: right; font-size: 11px; color: #888; }
    </style>
</head>
<body>
    <div class="header">
        <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/Logo-hospital.png" class="logo" alt="Al-Amine RDV" />
        <div>
            <div class="title">Rapport d'activité</div>
            <div class="period">Période du {{ Carbon::parse($dateDebut)->format('d/m/Y') }} au {{ Carbon::parse($dateFin)->format('d/m/Y') }}</div>
        </div>
    </div>

    <table>
        <tr>
            <th>Indicateur</th>
            <th>Valeur</th>
        </tr>
        <tr>
            <td>Rendez-vous programmés</td>
            <td>{{ $data['rdv_total'] }}</td>
        </tr>
        <tr>
            <td>Consultations réalisées</td>
            <td>{{ $data['consultations_total'] }}</td>
        </tr>
        <tr>
            <td>Nouveaux patients</td>
            <td>{{ $data['nouveaux_patients'] }}</td>
        </tr>
        <tr>
            <td>Taux de présence</td>
            <td>{{ $data['taux_presence'] }} %</td>
        </tr>
    </table>

    <div class="muted">Rapport généré automatiquement le {{ now()->format('d/m/Y à H:i') }}.</div>
    <div class="footer">Al-Amine RDV &copy; {{ date('Y') }}</div>
</body>
</html>
