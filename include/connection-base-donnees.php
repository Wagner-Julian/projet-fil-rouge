<?php

$host = 'localhost';
$nomDatabase = 'club_canin';
$utilisateur = 'root';
$motDePasse = '';

// try {
//     $pdo = new PDO("mysql:host=$host;dbname=$nomDatabase;charset=utf8", $utilisateur, $motDePasse);
//     $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//     error_log($e-> getMessage() . "\n" ,3, "public/ressources/error.log");
//     die("Erreur de connexion : " . $e->getMessage());
// }

try {
    $pdo = new PDO("mysql:host=$host;dbname=$nomDatabase;charset=utf8", $utilisateur, $motDePasse);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (PDOException $e) {
    echo "Une erreur est survenue : " . $e->getMessage(); // temporaire pour test
    error_log("Erreur PDO : " . $e->getMessage() . "\n", 3, "../public/ressources/erreurs.log");
}
