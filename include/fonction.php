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
function hsc($string){
    return htmlspecialchars($string);
}

