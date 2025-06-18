<?php
session_start();

require_once __DIR__ . '/../include/connection-base-donnees.php';
require_once __DIR__ . '/../include/fonction.php';

// Sécurité : l'utilisateur doit être connecté
if (!isset($_SESSION['id_utilisateur'])) {
    die("Utilisateur non connecté.");
}

$id_utilisateur = $_SESSION['id_utilisateur'];

// Récupération des informations de l'utilisateur
$stmtUtilisateur = $pdo->prepare("SELECT nom, prenom, email, nom_utilisateur FROM utilisateur WHERE id_utilisateur = :id");
$stmtUtilisateur->execute([':id' => $id_utilisateur]);
$utilisateur = $stmtUtilisateur->fetch(PDO::FETCH_ASSOC);

// Récupération des chiens de l'utilisateur
$stmtChiens = $pdo->prepare("SELECT c.nom_chien, c.date_naissance_chien, r.nom_race FROM chien c JOIN race r ON c.id_race = r.id_race WHERE c.id_utilisateur = :id ORDER BY c.id_chien DESC");
$stmtChiens->execute([':id' => $id_utilisateur]);
$chiens = $stmtChiens->fetchAll(PDO::FETCH_ASSOC);

// Récupération des cours disponibles
$stmtCours = $pdo->prepare("
    SELECT
        c.id_cours,
        c.nom_cours,
        c.date_cours,
        c.heure_cours,
        (c.nb_places_cours - COUNT(r.id_reservation)) AS places_restantes,
        t.age_min_mois,
        t.age_max_mois
    FROM cours c
    LEFT JOIN tranche_age t ON c.id_tranche = t.id_tranche
    LEFT JOIN reservation r ON r.id_cours = c.id_cours
    GROUP BY c.id_cours
    ORDER BY c.date_cours ASC
");
$stmtCours->execute();
$coursDisponibles = $stmtCours->fetchAll(PDO::FETCH_ASSOC);
foreach ($coursDisponibles as &$cours) {
    $cours['date_cours'] = dateFormatEurope($cours['date_cours']);
}
unset($cours);

// Récupération des réservations de l'utilisateur
$stmtResa = $pdo->prepare("
    SELECT c.nom_cours, c.date_cours, c.heure_cours, ch.nom_chien
    FROM reservation r
    JOIN cours c ON r.id_cours = c.id_cours
    JOIN chien ch ON r.id_chien = ch.id_chien
    WHERE ch.id_utilisateur = :id
    ORDER BY c.date_cours ASC
");
$stmtResa->execute([':id' => $id_utilisateur]);
$reservations = $stmtResa->fetchAll(PDO::FETCH_ASSOC);
foreach ($reservations as &$reservation) {
    $reservation['date_cours'] = dateFormatEurope($reservation['date_cours']);
}
unset($reservation);

require_once __DIR__ . '/../templates/tableauBord.html.php';