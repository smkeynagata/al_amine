<?php

namespace App\Http\Controllers\Traits;

use App\Models\Praticien;
use App\Models\RendezVous;
use Carbon\Carbon;

trait ManagesRendezVous
{
    protected int $slotDuration = 30;

    protected function hasConflict(Praticien $praticien, Carbon $debut, int $duree, ?int $ignoreRendezVousId = null): bool
    {
        $fin = $debut->copy()->addMinutes($duree);

        $rdvs = RendezVous::where('praticien_id', $praticien->id)
            ->when($ignoreRendezVousId, fn($query) => $query->where('id', '!=', $ignoreRendezVousId))
            ->where('date_heure_rdv', '<', $fin)
            ->get(['id', 'date_heure_rdv', 'duree']);

        foreach ($rdvs as $rdv) {
            $start = $rdv->date_heure_rdv->copy();
            $end = $start->copy()->addMinutes($rdv->duree ?? $this->slotDuration);

            if ($debut->lt($end) && $fin->gt($start)) {
                return true;
            }
        }

        return false;
    }

    protected function creneauDansDisponibilite(Praticien $praticien, Carbon $debut, int $duree): bool
    {
        // Les disponibilités ne sont plus bloquantes côté secrétaire :
        // on laisse la validation se concentrer sur les conflits de rendez-vous.
        return true;
    }
}
