<?php

session_start();

require_once __DIR__ . '/../include/connection-base-donnees.php';
require_once __DIR__ . '/../include/config.php';

require_once __DIR__ . '/../include/fonction.php';

$id_utilisateur = $_SESSION['id_utilisateur'] ?? null;

// Sécurité : vérifier que l'utilisateur est bien connecté
if (!isset($_SESSION['id_utilisateur'])) {
    die("Utilisateur non connecté.");
}

// Annulation d'une réservation
if (isset($_GET['annuler'])) {
    $idReservation = (int) $_GET['annuler'];
    $idUtilisateur = $_SESSION['id_utilisateur'];

    $sqlVerif = "SELECT r.id_reservation
        FROM reservation r
        JOIN chien c ON r.id_chien = c.id_chien
        WHERE r.id_reservation = :id_reservation
        AND c.id_utilisateur = :id_utilisateur";
    $stmtVerif = $pdo->prepare($sqlVerif);
    $stmtVerif->execute([
        ':id_reservation' => $idReservation,
        ':id_utilisateur' => $idUtilisateur
    ]);
    $reservation = $stmtVerif->fetch(PDO::FETCH_ASSOC);

    if ($reservation) {
        $stmtDelete = $pdo->prepare("DELETE FROM reservation WHERE id_reservation = :id_reservation");
        $stmtDelete->execute([':id_reservation' => $idReservation]);
        $_SESSION['message'] = "✅ Réservation annulée avec succès.";
    }

    header('Location: profil.php');
    exit;
}

// Récupération des données utilisateur
$sql = "SELECT nom, prenom, email, nom_utilisateur FROM utilisateur WHERE id_utilisateur = :id_utilisateur";
$stmt = $pdo->prepare($sql);
$stmt->execute([':id_utilisateur' => $id_utilisateur]);
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

// Vérifie si des données ont été trouvées
if ($utilisateur) {
    $nom = $utilisateur['nom'];
    $prenom = $utilisateur['prenom'] ?? '';
    $email = $utilisateur['email'];
    $nomUtilisateur = $utilisateur['nom_utilisateur'];
} else {
    die("Utilisateur introuvable.");
}


$stmtReservations = $pdo->prepare("
SELECT r.id_reservation, c.nom_cours, c.date_cours, c.heure_cours, ch.nom_chien
FROM reservation r
INNER JOIN cours c ON c.id_cours = r.id_cours
INNER JOIN chien ch ON ch.id_chien = r.id_chien
WHERE ch.id_utilisateur = :id_utilisateur
ORDER BY c.date_cours ASC
");
$stmtReservations->execute([':id_utilisateur' => $id_utilisateur]);
$reservationsUtilisateur = $stmtReservations->fetchAll(PDO::FETCH_ASSOC);


$stmtUtilisateur = $pdo->prepare("SELECT nom_utilisateur, email, nom, prenom FROM utilisateur WHERE id_utilisateur = :id_utilisateur");
$stmtUtilisateur->execute([':id_utilisateur' => $id_utilisateur]);
$utilisateurInfos = $stmtUtilisateur->fetch(PDO::FETCH_ASSOC);


// Inclusion de la vue
require_once __DIR__ . '/../templates/profil.html.php';
