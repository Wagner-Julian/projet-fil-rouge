<?php

$host = 'localhost';
$nomDatabase = 'club_canin';
$utilisateur = 'root';
$motDePasse = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$nomDatabase;charset=utf8", $utilisateur, $motDePasse);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

