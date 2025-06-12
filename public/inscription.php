<?php
session_start();
require_once __DIR__ . '/../include/connection-base-donnees.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? null;
    $prenom = $_POST['prenom'] ?? null;
    $email = $_POST['email'] ?? null;
    $dateInscription = (new DateTime())->format('Y-m-d H:i:s');
    $nomUtilisateur = $_POST['nom_utilisateur'] ?? null;
    $motDePasse = password_hash($_POST['mot_de_passe'] ?? "", PASSWORD_BCRYPT);
    $confirmationMotDePasse = $_POST['confirmation_mot_de_passe'] ?? null;
    
    // 🔍 Vérifier si l’email ou le nom d'utilisateur existe déjà
    $sql = "SELECT COUNT(*) FROM utilisateur WHERE email = :email OR nom_utilisateur = :nom_utilisateur";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':email' => $email,
        ':nom_utilisateur' => $nomUtilisateur
    ]);

    if ($stmt->fetchColumn() > 0) {
        die("L'email ou le nom d'utilisateur est déjà utilisé.");
    }

    // ✅ Insertion de l'utilisateur
    $sql = "INSERT INTO utilisateur (nom, prenom, email, date_inscription, nom_utilisateur, mot_de_passe, id_role)
            VALUES (:nom, :prenom, :email, :date_inscription, :nom_utilisateur, :mot_de_passe, :id_role)";
    $stmt = $pdo->prepare($sql);
    $idRole = 3; // rôle par défaut
    $stmt->execute([
        ':nom' => $nom,
        'prenom' => $_POST['prenom'] ?? null, 
        ':email' => $email,
        ':date_inscription' => $dateInscription,
        ':nom_utilisateur' => $nomUtilisateur,
        ':mot_de_passe' => $motDePasse,
        ':id_role' => $idRole
    ]);

    $idUtilisateur = $pdo->lastInsertId();
    $_SESSION['utilisateur-inscrit'] = true;

        header("Location: login.php");
    exit();
}



require_once __DIR__ . '/../templates/inscription.html.php';
