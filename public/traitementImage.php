<?php
session_start();

$idUtilisateur = $_SESSION['id_utilisateur'] ?? null;
if (!$idUtilisateur) {
    $_SESSION['message'] = "Erreur : utilisateur non connecté.";
    header("Location: profil.php");
    exit;
}

if (
    isset($_POST['update_photo']) &&
    isset($_FILES['user_image']) &&
    $_FILES['user_image']['error'] === 0
) {
    $extension  = strtolower(pathinfo($_FILES["user_image"]["name"], PATHINFO_EXTENSION));
    $allowed    = ["jpg", "jpeg", "png", "gif", "webp"];

    if (!in_array($extension, $allowed)) {
        $_SESSION['message'] = "Type de fichier non autorisé.";
        header("Location: profil.php");
        exit;
    }

    /* Dossier /public/ressources/telechargement/ */
    $uploadDir = __DIR__ . "/ressources/telechargement/";
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    /* On supprime toute ancienne photo de l’utilisateur  */
    foreach ($allowed as $ext) {
        $old = $uploadDir . "profil-$idUtilisateur.$ext";
        if (file_exists($old)) unlink($old);
    }

    /* On enregistre la nouvelle */
    $newName     = "profil-$idUtilisateur.$extension";
    $destination = $uploadDir . $newName;

    if (!move_uploaded_file($_FILES["user_image"]["tmp_name"], $destination)) {
        $_SESSION['message'] = "Erreur lors de l’enregistrement du fichier.";
        header("Location: profil.php");
        exit;
    }

    $_SESSION['message'] = "✅ Photo de profil mise à jour !";
    header("Location: profil.php");
    exit;
}

$_SESSION['message'] = "Aucune image reçue.";
header("Location: profil.php");
exit;
