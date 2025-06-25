<?php
session_start();
require_once __DIR__.'/../include/config.php';


// Récupération de l'identifiant utilisateur en session
$idUtilisateur = $_SESSION['id_utilisateur'] ?? null;
if (!$idUtilisateur) {
    $_SESSION['message'] = "Erreur : utilisateur non connecté.";
    header('Location: profil.php');
    exit;
}

if (isset($_FILES['user_image'])
    && $_FILES['user_image']['error'] === UPLOAD_ERR_OK) {

    $extension = strtolower(pathinfo($_FILES['user_image']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','gif','webp'];

    if (!in_array($extension, $allowed, true)) {
        $_SESSION['message'] = "Type de fichier non autorisé.";
        header('Location: profil.php');
        exit;
    }

    // Enregistrement
    $uploadDir = UPLOAD_DISK;                 // constante de config.php
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    $newName     = "profil-$idUtilisateur.$extension";
    $destination = $uploadDir.$newName;

    if (!move_uploaded_file($_FILES['user_image']['tmp_name'], $destination)) {
        $_SESSION['message'] = "Erreur lors de l'enregistrement du fichier.";
        header('Location: profil.php');
        exit;
    }

    $_SESSION['message'] = "✅ Photo de profil mise à jour !";
    header('Location: profil.php');
    exit;
}

// Gestion des erreurs éventuelles lors de l'envoi du fichier
if (!isset($_FILES['user_image'])) {
    $_SESSION['message'] = "Aucun fichier reçu (formulaire incorrect ou taille > post_max_size).";
    header('Location: profil.php');
    exit;
}

if ($_FILES['user_image']['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['message'] =
        "Erreur lors de l'envoi du fichier (code ".$_FILES['user_image']['error'].").";
    header('Location: profil.php');
    exit;
}


$_SESSION['message'] = "Aucune image reçue.";
header('Location: profil.php');
exit;