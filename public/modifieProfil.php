<?php
session_start();

require_once __DIR__ . '/../include/connection-base-donnees.php';
require_once __DIR__ . '/../include/fonction.php';

$idUtilisateur = $_SESSION['id_utilisateur'] ?? null;

if (!$idUtilisateur) {
    die("Utilisateur non connecté.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    // UPDATE utilisateur
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $nomUtilisateur = $_POST['nom_utilisateur'] ?? '';
    
    // 🔍 Vérifier si l’email ou le nom d'utilisateur existe déjà
    $sql = "SELECT COUNT(*) FROM utilisateur WHERE (email = :email OR nom_utilisateur = :nom_utilisateur) AND id_utilisateur<>:id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':email' => $email,
        ':nom_utilisateur' => $nomUtilisateur,
        ':id' => $idUtilisateur
    ]);
        if ($stmt->fetchColumn() > 0) {
        die("L'email ou le nom d'utilisateur est déjà utilisé.");
    }
    $sqlUpdateUtilisateur = "UPDATE utilisateur
                            SET nom = :nom,
                                prenom = :prenom,
                                email = :email,
                                nom_utilisateur = :nom_utilisateur
                            WHERE id_utilisateur = :id_utilisateur";

$stmtUpdateUtilisateur = $pdo->prepare($sqlUpdateUtilisateur);
$stmtUpdateUtilisateur->execute([
    ':nom' => $nom,
    ':prenom' => $prenom,
    ':email' => $email,
    ':nom_utilisateur' => $nomUtilisateur,
    ':id_utilisateur' => $idUtilisateur
]);

// Message de succès
$_SESSION['profil_modifie'] = true;

// Redirection pour éviter le re-post du formulaire
header("Location: modifieProfil.php");
    exit();
}


$sql = "SELECT nom, prenom, email, nom_utilisateur, date_inscription
        FROM utilisateur
        WHERE id_utilisateur = :id_utilisateur";


$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_utilisateur', $_SESSION['id_utilisateur']);
$stmt->execute();

$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

if ($utilisateur) {
    $nom = $utilisateur['nom'];
    $prenom = $utilisateur['prenom'];
    $email = $utilisateur['email'];
    $nomUtilisateur = $utilisateur['nom_utilisateur'];
    $dateInscription = $utilisateur['date_inscription'];
} else {
    $nom = $prenom = $email = $nomUtilisateur = $dateInscription = '';
}

require_once __DIR__ . '/../templates/modifieProfil.html.php';