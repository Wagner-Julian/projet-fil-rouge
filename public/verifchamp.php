<?php
require_once __DIR__ . '/../include/connection-base-donnees.php';



if (isset($_GET['email'])) {
    $email = $_GET['email'];

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    $count = $stmt->fetchColumn();
    echo json_encode(['champ' => 'email', 'existe' => ($count > 0)]);
    exit;
}

if (isset($_GET['nom_utilisateur'])) {
    $nom_utilisateur = $_GET['nom_utilisateur'];

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM utilisateur WHERE nom_utilisateur = :nom_utilisateur");
    $stmt->bindParam(':nom_utilisateur', $nom_utilisateur);
    $stmt->execute();

    $count = $stmt->fetchColumn();
    echo json_encode(['champ' => 'nom_utilisateur', 'existe' => ($count > 0)]);
    exit;
}

// Si aucun paramètre n'est fourni
echo json_encode(['error' => 'Paramètre manquant']);
exit;


