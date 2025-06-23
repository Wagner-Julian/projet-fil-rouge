<?php

function ageChien($dateNaissanceChien)
{
    $dateNaissance = new DateTime($dateNaissanceChien);
    $aujourdhui = new DateTime();

    $age = $aujourdhui->diff($dateNaissance);
    if ($age->y == 0) {
        return $age->m . ' mois ';
    } elseif ($age->y < 2 && $age->m > 0) {
        return $age->y . ' an ' . $age->m . ' mois ';
    } elseif ($age->y < 2) {
        return $age->y . ' an ';
    } elseif ($age->m == 0) {
        return $age->y . ' ans ';
    } else {
        return $age->y . ' ans ' . $age->m . ' mois';
    }
}
function hsc($string)
{
    return htmlspecialchars($string);
}


// 🔒 Vérification de la date
function dateFormatUniversel($date)
{
    if ($date && !is_null($date)) {
        $dateObj = DateTime::createFromFormat('d/m/Y', $date);

        if (!$dateObj || $dateObj->format('d/m/Y') !== $date) {
            die("Format de date invalide. Format attendu : jj/mm/aaaa.");
        }

        return $date = $dateObj->format('Y-m-d');
    }
}

function dateFormatEurope($date)
{
    if ($date && !is_null($date)) {
        $dateObj = DateTime::createFromFormat('Y-m-d', $date);
        return $date = $dateObj->format('d/m/Y');
    }
}


function formatDuree($minutes) {
    if ($minutes === null || $minutes === '') {
        return 'Non renseignée';
    }
    if ($minutes < 60) {
        return $minutes . ' min';
    }
    $heures = floor($minutes / 60);
    $reste = $minutes % 60;
    return $heures . ' h' . ($reste ? ' ' . $reste . ' min' : '');
}

function convertirHeure($heure) {
    $dateTime = DateTime::createFromFormat('H:i:s', $heure);

if($dateTime) {
    return $dateTime->format('G\hi');
} else {
    return 'Heure invalide';
}

}