<?php
session_start();

require_once __DIR__ . '/../include/connection-base-donnees.php';
require_once __DIR__ . '/../include/fonction.php';


if (!isset($_SESSION['id_utilisateur'])) {
    die("Utilisateur non connecté.");
}

$idUtilisateur = $_POST['id_utilisateur'] ?? null;
$nouveauRole = $_POST['nouveau_role'] ?? null;

if ($idUtilisateur && $nouveauRole) {
    $stmtRole = $pdo->prepare("SELECT id_role FROM role WHERE nom_role = :nom_role");
    $stmtRole->execute([':nom_role' => $nouveauRole]);
    $role = $stmtRole->fetch(PDO::FETCH_ASSOC);

    if ($role) {
        $idRole = $role['id_role'];

        $stmtUpdate = $pdo->prepare("UPDATE utilisateur
            SET id_role = :id_role
            WHERE id_utilisateur = :id_utilisateur");
        $stmtUpdate->execute([
            ':id_role' => $idRole,
            ':id_utilisateur' => $idUtilisateur
        ]);

        $_SESSION['role_modifie'] = true;
    }

    // Redirection pour recharger la page en GET (PRG pattern)
    header("Location: admin.php");
    exit();
}

if (isset($_POST['supprimer_utilisateur'])) {
    $idUtilisateurASupprimer = $_POST['supprimer_utilisateur'];

    // Ne pas permettre la suppression de soi-même
    if ($idUtilisateurASupprimer != $_SESSION['id_utilisateur']) {

        $stmtDeleteChien = $pdo->prepare("DELETE FROM chien WHERE id_utilisateur = :id_utilisateur");
        $stmtDeleteChien->execute([':id_utilisateur' => $idUtilisateurASupprimer]);

        $stmtDeleteUtilisateur = $pdo->prepare("DELETE FROM utilisateur WHERE id_utilisateur = :id_utilisateur");
        $stmtDeleteUtilisateur->execute([':id_utilisateur' => $idUtilisateurASupprimer]);

        $_SESSION['utilisateur_supprime'] = true;
    } else {
        $_SESSION['erreur_suppression'] = "Vous ne pouvez pas supprimer votre propre compte.";
    }

    header("Location: admin.php");
    exit();
}


// Toujours récupérer les membres pour affichage
$sql = "SELECT u.id_utilisateur, CONCAT(u.nom_utilisateur,CHAR(10), u.email) AS infos , r.nom_role
        FROM utilisateur u
        JOIN role r ON u.id_role = r.id_role
        ORDER BY u.nom_utilisateur";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$informationsUtilisateur = $stmt->fetchAll(PDO::FETCH_ASSOC);

$membres = [];

foreach ($informationsUtilisateur as $ligne) {
    $id = $ligne['id_utilisateur'];
    $membres[$id] = [
        'infos' => $ligne['infos'],
        'nom_role' => $ligne['nom_role'],
    ];
}


require_once __DIR__ . '/../templates/admin.html.php';