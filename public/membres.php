<?php
session_start();

require_once __DIR__ . '/../include/connection-base-donnees.php';
require_once __DIR__ . '/../include/fonction.php';

// Sécurité : vérifier que l'utilisateur est bien connecté
if (!isset($_SESSION['id_utilisateur'])) {
    die("Utilisateur non connecté.");
}

// Récupération des données utilisateur
$sql = "
    SELECT u.id_utilisateur, u.nom_utilisateur, c.nom_chien, r.nom_race, c.date_naissance_chien
    FROM utilisateur u
    JOIN chien c ON u.id_utilisateur = c.id_utilisateur
    JOIN race r ON c.id_race = r.id_race
    ORDER BY u.nom_utilisateur
";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$informationsUtilisateur = $stmt->fetchAll(PDO::FETCH_ASSOC);

$membres = [];

foreach ($informationsUtilisateur as $ligne) {
    $id = $ligne['id_utilisateur'];
    if (!isset($membres[$id])) {
        $membres[$id] = [
            'nom_utilisateur' => $ligne['nom_utilisateur'],
            'chiens' => [],
        ];
    }
    $membres[$id]['chiens'][] = [
        'nom_chien' => $ligne['nom_chien'],
        'nom_race' => $ligne['nom_race'],
        'date_naissance' => $ligne['date_naissance_chien'],
    ];
}



require_once __DIR__ . '/../templates/membres.html.php';