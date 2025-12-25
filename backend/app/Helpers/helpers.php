<?php

if (!function_exists('formatCurrency')) {
    function formatCurrency($amount)
    {
        return number_format($amount, 0, ',', ' ') . ' FCFA';
    }
}

if (!function_exists('formatPhone')) {
    function formatPhone($phone)
    {
        // Format: 77 123 45 67
        if (strlen($phone) === 9) {
            return substr($phone, 0, 2) . ' ' . substr($phone, 2, 3) . ' ' . substr($phone, 5, 2) . ' ' . substr($phone, 7, 2);
        }
        return $phone;
    }
}

if (!function_exists('calculateAge')) {
    function calculateAge($dateNaissance)
    {
        if (!$dateNaissance) return null;

        if (is_string($dateNaissance)) {
            $dateNaissance = \Carbon\Carbon::parse($dateNaissance);
        }

        return $dateNaissance->age;
    }
}

if (!function_exists('generateReference')) {
    function generateReference($prefix = 'REF')
    {
        return strtoupper($prefix) . '-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
    }
}

if (!function_exists('quartiersDatekar')) {
    function quartiersDatekar()
    {
        return [
            'Plateau',
            'Médina',
            'Parcelles Assainies',
            'Liberté 6',
            'Mermoz',
            'HLM Grand Yoff',
            'Ouakam',
            'Almadies',
            'Ngor',
            'Yoff',
            'Sicap Liberté',
            'Fann',
            'Point E',
            'Amitié',
            'Sacré-Coeur',
            'Dieuppeul',
            'Grand Dakar',
            'Gueule Tapée',
            'Fass',
            'Colobane',
        ];
    }
}

if (!function_exists('getStatutBadgeClass')) {
    function getStatutBadgeClass($statut)
    {
        return match($statut) {
            'EN_ATTENTE', 'PLANIFIE', 'BROUILLON' => 'bg-yellow-100 text-yellow-800',
            'CONFIRMEE', 'CONFIRME', 'VALIDE', 'EMISE' => 'bg-green-100 text-green-800',
            'REFUSEE', 'ANNULE', 'ANNULEE', 'ECHOUE' => 'bg-red-100 text-red-800',
            'EN_COURS' => 'bg-blue-100 text-blue-800',
            'TERMINE', 'PAYEE' => 'bg-green-600 text-white',
            'ABSENT' => 'bg-gray-100 text-gray-800',
            'ACTIF' => 'bg-green-100 text-green-800',
            'SUSPENDU' => 'bg-orange-100 text-orange-800',
            'DESACTIVE' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}

