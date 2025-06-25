<?php
session_start();
require_once __DIR__.'/../include/config.php';

$idUtilisateur = $_SESSION['id_utilisateur'] ?? null;
if (!$idUtilisateur) {
    $_SESSION['message'] = "Erreur : utilisateur non connecté.";
    header('Location: profil.php');
    exit;
}

if (isset($_FILES['user_image']) && $_FILES['user_image']['error'] === UPLOAD_ERR_OK) {
    $tmpPath   = $_FILES['user_image']['tmp_name'];
    $origName  = $_FILES['user_image']['name'];
    $extension = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
    $allowed   = ['jpg', 'jpeg', 'png', 'gif', 'webp'];

    if (!in_array($extension, $allowed, true)) {
        $_SESSION['message'] = "Type de fichier non autorisé.";
        header('Location: profil.php');
        exit;
    }

    // Prépare le répertoire
    $uploadDir = UPLOAD_DISK;
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    // Chemin final en .webp
    $destinationWebp = $uploadDir . "profil-$idUtilisateur.webp";

    // Chargement image selon extension
    switch ($extension) {
        case 'jpg':
        case 'jpeg':
            $image = imagecreatefromjpeg($tmpPath);
            break;
        case 'png':
            $image = imagecreatefrompng($tmpPath);
            break;
        case 'gif':
            $image = imagecreatefromgif($tmpPath);
            break;
        case 'webp':
            $image = imagecreatefromwebp($tmpPath);
            break;
        default:
            $_SESSION['message'] = "Erreur : format non supporté.";
            header('Location: profil.php');
            exit;
    }

    if (!$image) {
        $_SESSION['message'] = "Erreur : chargement de l'image impossible.";
        header('Location: profil.php');
        exit;
    }

    // Conversion → WEBP (qualité 80)
    if (!imagewebp($image, $destinationWebp, 80)) {
        $_SESSION['message'] = "Erreur lors de la conversion WebP.";
        header('Location: profil.php');
        exit;
    }

    imagedestroy($image);

    $_SESSION['message'] = "✅ Photo enregistrée.";
    header('Location: profil.php');
    exit;
}

// Gestion des erreurs
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