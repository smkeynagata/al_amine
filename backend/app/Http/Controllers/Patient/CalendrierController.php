<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use App\Models\RendezVous;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendrierController extends Controller
{
    public function index()
    {
        return view('patient.calendrier.index');
    }

    public function getEvents(Request $request)
    {
        $patient = auth()->user()->patient;
        
        $start = $request->input('start');
        $end = $request->input('end');

        $rendezVous = RendezVous::where('patient_id', $patient->id)
            ->with(['praticien.user', 'praticien.specialites'])
            ->whereBetween('date_heure_rdv', [$start, $end])
            ->get();

        $events = $rendezVous->map(function ($rdv) {
            $color = match($rdv->statut) {
                'CONFIRME' => '#3b82f6', // blue
                'EN_ATTENTE' => '#f59e0b', // orange
                'TERMINE' => '#10b981', // green
                'ANNULE' => '#ef4444', // red
                default => '#6b7280', // gray
            };

            return [
                'id' => $rdv->id,
                'title' => 'Dr. ' . $rdv->praticien->user->nom_complet,
                'start' => $rdv->date_heure_rdv->toIso8601String(),
                'end' => $rdv->date_heure_rdv->copy()->addMinutes($rdv->duree_minutes ?? 30)->toIso8601String(),
                'backgroundColor' => $color,
                'borderColor' => $color,
                'extendedProps' => [
                    'praticien' => $rdv->praticien->user->nom_complet,
                    'specialite' => $rdv->praticien->specialites->first()?->nom ?? 'N/A',
                    'statut' => $rdv->statut_display,
                    'motif' => $rdv->motif,
                    'bureau' => $rdv->praticien->numero_bureau,
                ],
            ];
        });

        return response()->json($events);
    }

    public function exportIcal()
    {
        $patient = auth()->user()->patient;
        
        $rendezVous = RendezVous::where('patient_id', $patient->id)
            ->where('statut', '!=', 'ANNULE')
            ->with(['praticien.user'])
            ->get();

        $ical = "BEGIN:VCALENDAR\r\n";
        $ical .= "VERSION:2.0\r\n";
        $ical .= "PRODID:-//Al-Amine//Calendrier Patient//FR\r\n";
        $ical .= "CALSCALE:GREGORIAN\r\n";
        $ical .= "METHOD:PUBLISH\r\n";
        $ical .= "X-WR-CALNAME:Mes Rendez-vous Médicaux\r\n";
        $ical .= "X-WR-TIMEZONE:Africa/Dakar\r\n";

        foreach ($rendezVous as $rdv) {
            $dtstart = $rdv->date_heure_rdv->format('Ymd\THis');
            $dtend = $rdv->date_heure_rdv->copy()->addMinutes($rdv->duree_minutes ?? 30)->format('Ymd\THis');
            $dtstamp = now()->format('Ymd\THis');
            
            $ical .= "BEGIN:VEVENT\r\n";
            $ical .= "UID:" . $rdv->id . "@al-amine.sn\r\n";
            $ical .= "DTSTAMP:{$dtstamp}\r\n";
            $ical .= "DTSTART:{$dtstart}\r\n";
            $ical .= "DTEND:{$dtend}\r\n";
            $ical .= "SUMMARY:RDV - Dr. " . $rdv->praticien->user->nom_complet . "\r\n";
            $ical .= "DESCRIPTION:Rendez-vous médical avec Dr. " . $rdv->praticien->user->nom_complet;
            if ($rdv->motif) {
                $ical .= " - Motif: " . $rdv->motif;
            }
            $ical .= "\r\n";
            $ical .= "LOCATION:Cabinet - " . ($rdv->praticien->numero_bureau ?? 'À confirmer') . "\r\n";
            $ical .= "STATUS:" . ($rdv->statut === 'CONFIRME' ? 'CONFIRMED' : 'TENTATIVE') . "\r\n";
            $ical .= "SEQUENCE:0\r\n";
            $ical .= "END:VEVENT\r\n";
        }

        $ical .= "END:VCALENDAR\r\n";

        return response($ical)
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="mes-rdv-medicaux.ics"');
    }

    public function exportGoogle()
    {
        $patient = auth()->user()->patient;
        
        // Récupérer le prochain RDV
        $rdv = RendezVous::where('patient_id', $patient->id)
            ->where('date_heure_rdv', '>', now())
            ->where('statut', '!=', 'ANNULE')
            ->with(['praticien.user'])
            ->orderBy('date_heure_rdv')
            ->first();

        if (!$rdv) {
            return back()->with('error', 'Aucun rendez-vous à exporter');
        }

        $title = 'RDV - Dr. ' . $rdv->praticien->user->nom_complet;
        $details = 'Rendez-vous médical';
        if ($rdv->motif) {
            $details .= ' - ' . $rdv->motif;
        }
        $location = 'Cabinet - ' . ($rdv->praticien->numero_bureau ?? 'À confirmer');
        
        $startTime = $rdv->date_heure_rdv->format('Ymd\THis');
        $endTime = $rdv->date_heure_rdv->copy()->addMinutes($rdv->duree_minutes ?? 30)->format('Ymd\THis');

        $googleCalendarUrl = 'https://www.google.com/calendar/render?action=TEMPLATE'
            . '&text=' . urlencode($title)
            . '&dates=' . $startTime . '/' . $endTime
            . '&details=' . urlencode($details)
            . '&location=' . urlencode($location);

        return redirect($googleCalendarUrl);
    }
}
