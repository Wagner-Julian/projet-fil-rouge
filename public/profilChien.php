<?php

session_start();

if (!empty($_SESSION['chien_inscrit'])) {
    echo "<script>alert('🐶 Chien inscrit avec succès !');</script>";
    unset($_SESSION['chien_inscrit']);
}

require_once __DIR__ . '/../include/connection-base-donnees.php';
require_once __DIR__ . '/../include/fonction.php';
require_once __DIR__ . '/../include/config.php';



$idUtilisateur = $_SESSION['id_utilisateur'] ?? null;
$dateInscription = date('Y-m-d');

if (!$idUtilisateur) {
    die("Erreur : Utilisateur non connecté.");
}


// Récupérer les infos du chien si elles existent déjà
$stmt = $pdo->prepare("
    SELECT c.id_chien, c.nom_chien, c.date_naissance_chien, r.nom_race
    FROM chien c
    JOIN race r ON c.id_race = r.id_race
    WHERE c.id_utilisateur = :id
    ORDER BY c.id_chien DESC
");

$stmt->execute(['id' => $idUtilisateur]);
$chiens = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Définit les variables, même si on n'a pas posté
$nomChien = $chien['nom_chien'] ?? null;
$raceChien = $chien['nom_race'] ?? null;
$dateNaissanceChien = $chien['date_naissance_chien'] ?? null;


require_once __DIR__ . '/../templates/profilChien.html.php';
