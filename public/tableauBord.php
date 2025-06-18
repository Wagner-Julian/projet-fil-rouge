<?php
session_start();

require_once __DIR__ . '/../include/connection-base-donnees.php';
require_once __DIR__ . '/../include/fonction.php';

// Vérification de la connexion de l'utilisateur
if (!isset($_SESSION['id_utilisateur'])) {
    die("Utilisateur non connecté.");
}

$idUtilisateur = $_SESSION['id_utilisateur'];

// Informations de l'utilisateur
$stmt = $pdo->prepare(
    "SELECT nom, prenom, email, nom_utilisateur FROM utilisateur WHERE id_utilisateur = :id"
);
$stmt->execute([':id' => $idUtilisateur]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

// Informations sur les chiens de l'utilisateur
$stmtChiens = $pdo->prepare(
    "SELECT c.nom_chien, r.nom_race, c.date_naissance_chien
     FROM chien c
     JOIN race r ON c.id_race = r.id_race
     WHERE c.id_utilisateur = :id
     ORDER BY c.id_chien"
);
$stmtChiens->execute([':id' => $idUtilisateur]);
$chiens = $stmtChiens->fetchAll(PDO::FETCH_ASSOC);

// Cours disponibles
$stmtCours = $pdo->prepare(
    "SELECT c.id_cours, c.nom_cours, c.date_cours, c.heure_cours,
            (c.nb_places_cours - COUNT(r.id_reservation)) AS nb_places,
            t.nom AS nom_tranche
     FROM cours c
     LEFT JOIN tranche_age t ON c.id_tranche = t.id_tranche
     LEFT JOIN reservation r ON r.id_cours = c.id_cours
     WHERE c.date_cours >= CURDATE()
     GROUP BY c.id_cours
     ORDER BY c.date_cours ASC"
);
$stmtCours->execute();
$coursDisponibles = $stmtCours->fetchAll(PDO::FETCH_ASSOC);
foreach ($coursDisponibles as &$cours) {
    $cours['date_cours'] = dateFormatEurope($cours['date_cours']);
}
unset($cours);

// Réservations de l'utilisateur
$stmtRes = $pdo->prepare(
    "SELECT c.nom_cours, c.date_cours, c.heure_cours
     FROM reservation r
     JOIN cours c ON r.id_cours = c.id_cours
     JOIN chien ch ON r.id_chien = ch.id_chien
     WHERE ch.id_utilisateur = :id
     ORDER BY c.date_cours ASC"
);
$stmtRes->execute([':id' => $idUtilisateur]);
$reservations = $stmtRes->fetchAll(PDO::FETCH_ASSOC);
foreach ($reservations as &$res) {
    $res['date_cours'] = dateFormatEurope($res['date_cours']);
}
unset($res);

require_once __DIR__ . '/../templates/tableauBord.html.php';
