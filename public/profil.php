<?php
session_start();

require_once __DIR__ . '/../include/connection-base-donnees.php';

$sql= "SELECT  u.nom, u.email, u.nom_utilisateur, c.nom_chien, c.date_naissance_chien, r.nom_race FROM utilisateur u JOIN chien c ON u.id_utilisateur = c.id_utilisateur JOIN race r ON c.id_race = r.id_race WHERE u.id_utilisateur = :id_utilisateur";

// recuperation des données de l'utilisateur
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id_utilisateur', $_SESSION['id_utilisateur']);
$stmt->execute();
$utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);

// ajout des données de l'utilisateur et du chien sur la page profil
if ($utilisateur) {
    $nom = $utilisateur['nom'];
    $email = $utilisateur['email'];
    $nomUtilisateur = $utilisateur['nom_utilisateur'];
    $nomChien = $utilisateur['nom_chien'];
    $dateNaissanceChien = $utilisateur['date_naissance_chien'];
    $raceChien = $utilisateur['nom_race'];
}

require_once __DIR__ . '/../templates/profil.html.php';



        