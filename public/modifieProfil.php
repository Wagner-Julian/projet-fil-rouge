<?php
session_start();

require_once __DIR__ . '/../include/connection-base-donnees.php';
require_once __DIR__ . '/../include/fonction.php';

$sql = "SELECT * FROM utilisateur WHERE id_utilisateur = :id_utilisateur";

// recuperation des données de l'utilisateur
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_utilisateur', $_SESSION['id_utilisateur']);
$stmt->execute();
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

// ajout des données de l'utilisateur et du chien sur la page profil
if ($utilisateur) {
    $nom = $utilisateur['nom'];
    $prenom = $utilisateur['prenom'] ?? ''; 
    $email = $utilisateur['email'];
    $nomUtilisateur = $utilisateur['nom_utilisateur'];

}








require_once __DIR__ . '/../templates/modifieProfil.html.php';