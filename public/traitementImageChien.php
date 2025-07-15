<?php
session_start();

require_once __DIR__.'/../include/config.php';
require_once __DIR__.'/../include/connection-base-donnees.php';

$idUtilisateur = $_SESSION['id_utilisateur'] ?? null;
if (!$idUtilisateur) {
    $_SESSION['message-chien'] = 'Erreur : utilisateur non connecté.';
    header('Location: profilChien.php');
    exit;
}

/*----------- validations de base -----------*/
$idChien = (int) ($_POST['id_chien']   ?? 0);
$photo   =        $_FILES['chien_image'] ?? null;

if (!$idChien || !$photo) {
    $_SESSION['message-chien'] = 'Aucun fichier reçu ou identifiant manquant.';
    header('Location: profilChien.php');
    exit;
}

if ($photo['error'] !== UPLOAD_ERR_OK) {
    $_SESSION['message-chien'] = 'Erreur lors de l’envoi du fichier (code '.$photo['error'].').';
    header('Location: profilChien.php');
    exit;
}

/*----------- sécurité : le chien appartient-il à l’utilisateur ? -----------*/
$stmt = $pdo->prepare("
    SELECT 1 FROM chien
    WHERE id_chien = :id_chien
    AND id_utilisateur = :id_user
");
$stmt->execute([
    ':id_chien' => $idChien,
    ':id_user'  => $idUtilisateur
]);
// securité si une personne essaie de modifier l'image d'un chien d'un autre utilisateur 
if (!$stmt->fetchColumn()) {
    $_SESSION['message-chien'] = 'Accès interdit : ce chien ne vous appartient pas.';
    header('Location: profilChien.php');
    exit;
}

/*----------- contrôle du fichier -----------*/
$allowed = ['jpg','jpeg','png','gif','webp'];
$ext = strtolower(pathinfo($photo['name'], PATHINFO_EXTENSION));
if (!in_array($ext, $allowed, true)) {
    $_SESSION['message'] = 'Type de fichier non autorisé.';
    header('Location: profilChien.php');
    exit;
}

/*----------- enregistrement -----------*/
if (!is_dir(UPLOAD_DISK)) mkdir(UPLOAD_DISK, 0755, true);

foreach ($allowed as $e) {                        // supprime l’ancienne
    $old = UPLOAD_DISK.'chien-'.$idChien.'.'.$e;
    if (is_file($old)) unlink($old);
}

$dest = UPLOAD_DISK.'chien-'.$idChien.'.'.$ext;
if (!move_uploaded_file($photo['tmp_name'], $dest)) {
    $_SESSION['message-chien'] = 'Erreur lors de la copie du fichier.';
    header('Location: profilChien.php');
    exit;
}

// supprimer l'image 
$_SESSION['message-chien'] = '✅ Photo du chien mise à jour !';
header('Location: profilChien.php');

