<?php
session_start();

require_once __DIR__ . '/../include/connection-base-donnees.php';
require_once __DIR__ . '/../include/fonction.php';

// Sécurité : vérifier que l'utilisateur est bien connecté
if (!isset($_SESSION['id_utilisateur'])) {
    die("Utilisateur non connecté.");
}

// Récupération des données utilisateur
$sql = "SELECT nom, email, nom_utilisateur FROM utilisateur WHERE id_utilisateur = :id_utilisateur";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id_utilisateur' => $_SESSION['id_utilisateur']]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifie si des données ont été trouvées
if ($utilisateur) {
    $nom = $utilisateur['nom'];
    $email = $utilisateur['email'];
    $nomUtilisateur = $utilisateur['nom_utilisateur'];
} else {
    die("Utilisateur introuvable.");
}

// Inclusion de la vue
require_once __DIR__ . '/../templates/profil.html.php';


