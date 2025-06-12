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

$sql = "SELECT  
    u.nom, u.prenom, u.email, u.nom_utilisateur,u.date_inscription, 
    c.id_chien, c.nom_chien, c.date_naissance_chien, c.date_inscription,
    r.nom_race 
FROM utilisateur u 
JOIN chien c ON u.id_utilisateur = c.id_utilisateur 
JOIN race r ON c.id_race = r.id_race 
WHERE u.id_utilisateur = :id_utilisateur";

$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_utilisateur', $_SESSION['id_utilisateur']);
$stmt->execute();

// récupérer tous les chiens
$chiens = $stmt->fetchAll(PDO::FETCH_ASSOC);
$nombreChiens = count($chiens);

// données utilisateur = première ligne (puisque c’est redondant pour chaque chien)
if (!empty($chiens)) {
    $nom = $chiens[0]['nom'];
    $prenom = $chiens[0]['prenom'] ?? '';
    $email = $chiens[0]['email'];
    $nomUtilisateur = $chiens[0]['nom_utilisateur'];
    $dateInscription = $chiens[0]['date_inscription'] ?? '';
    $nomChien = $chiens[0]['nom_chien'] ?? '';
    $raceChien = $chiens[0]['nom_race'] ?? '' ;
    $dateNaissance = $chiens[0]['date_naissance_chien'] ?? '';
    $dateInscriptionChien = $chiens[0]['date_inscription'] ??'';
} elseif (isset($_SESSION['id_utilisateur'])) {
} else {
    // Cas rare : aucun chien trouvé, données utilisateur vides
    $nom = $prenom = $email = $nomUtilisateur = '';
}

require_once __DIR__ . '/../templates/modifieProfil.html.php';