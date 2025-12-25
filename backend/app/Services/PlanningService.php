<?php

namespace App\Services;

use App\Models\Praticien;
use App\Models\RendezVous;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class PlanningService
{
    public const DAY_MAP = [
        Carbon::MONDAY => 'LUNDI',
        Carbon::TUESDAY => 'MARDI',
        Carbon::WEDNESDAY => 'MERCREDI',
        Carbon::THURSDAY => 'JEUDI',
        Carbon::FRIDAY => 'VENDREDI',
        Carbon::SATURDAY => 'SAMEDI',
        Carbon::SUNDAY => 'DIMANCHE',
    ];

    public const DEFAULT_START_HOUR = 8;
    public const DEFAULT_END_HOUR = 18; // heure de fin exclusive

    /**
     * Calcule les créneaux proposés (planning générique) et les rendez-vous existants d'un praticien.
     */
    public static function availability(Praticien $praticien, Carbon $start, Carbon $end, int $slotDuration = 30, int $limit = 60): array
    {
        $slots = [];
        $rdvs = ($praticien->rendezVous ?? collect())->map(function (RendezVous $rdv) use ($slotDuration) {
            $startAt = $rdv->date_heure_rdv->copy();
            $endAt = $startAt->copy()->addMinutes($rdv->duree ?? $slotDuration);

            return compact('startAt', 'endAt');
        });

        $period = CarbonPeriod::create($start->copy(), '1 day', $end->copy());
        $now = now();

        foreach ($period as $date) {
            if (count($slots) >= $limit) {
                break;
            }

            $dayStart = $date->copy()->setTime(self::DEFAULT_START_HOUR, 0);
            $dayEnd = $date->copy()->setTime(self::DEFAULT_END_HOUR, 0);

            while ($dayStart->lt($dayEnd)) {
                if (count($slots) >= $limit) {
                    break 2;
                }

                $slotEnd = $dayStart->copy()->addMinutes($slotDuration);

                if ($slotEnd->gt($dayEnd) || $dayStart->lt($now)) {
                    $dayStart->addMinutes($slotDuration);
                    continue;
                }

                $hasConflict = $rdvs->contains(function (array $rdv) use ($dayStart, $slotEnd) {
                    return $dayStart->lt($rdv['endAt']) && $slotEnd->gt($rdv['startAt']);
                });

                if (!$hasConflict) {
                    $slots[] = [
                        'value' => $dayStart->format('Y-m-d\TH:i'),
                        'label' => ucfirst($dayStart->locale('fr')->isoFormat('ddd D MMMM • HH:mm')),
                    ];
                }

                $dayStart->addMinutes($slotDuration);
            }
        }

        $rdvsFormatted = $rdvs->map(fn($rdv) => [
            'start' => $rdv['startAt']->format('Y-m-d\TH:i'),
            'end' => $rdv['endAt']->format('Y-m-d\TH:i'),
        ])->values();

        return [
            'slots' => $slots,
            'rdvs' => $rdvsFormatted,
        ];
    }
}
