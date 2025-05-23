<?php
session_start();

require_once __DIR__ . '/../include/connection-base-donnees.php';

var_dump($_POST);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ??null;
    $email = $_POST['email']??null;
    $dateInscription = date('Y-m-d')??null;
    $nomUtilisateur = $_POST['nom_utilisateur']??null;
    $motDePasse = password_hash($_POST['mot_de_passe']??"", PASSWORD_BCRYPT);
    $nomChien = $_POST['nom_chien']??null;
    $race = $_POST['race']??null;
 $dateNaissanceInput = $_POST['date_naissance'];  // valeur texte 'dd/mm/yyyy'

$dateNaissance = null;
if (!empty($dateNaissanceInput)) {
    $dateObj = DateTime::createFromFormat('d/m/Y', $dateNaissanceInput);
    if ($dateObj) {
        $dateNaissance = $dateObj->format('Y-m-d');
    } else {
        die("Erreur de format de date : $dateNaissanceInput");
    }
} else {
    die("Date de naissance du chien manquante.");
}




$sql = "INSERT INTO utilisateur (nom, email, date_inscription, nom_utilisateur, mot_de_passe, id_role)
        VALUES (:nom, :email, :date_inscription, :nom_utilisateur, :mot_de_passe, :id_role)";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':nom', $nom);
$stmt->bindParam(':email', $email);
$stmt->bindParam(':date_inscription', $dateInscription);
$stmt->bindParam(':nom_utilisateur', $nomUtilisateur);
$stmt->bindParam(':mot_de_passe', $motDePasse);
$stmt->bindParam(':id_role', $idRole);
$idRole = 3; 
$stmt->execute();
$idUtilisateur = $pdo->lastInsertId();

$sqlChien= "INSERT INTO chien (nom_chien,date_inscription,date_naissance_chien,id_utilisateur) VALUES (:nom_chien,:date_inscription,:date_naissance_chien, :id_utilisateur)";
$stmtChien = $pdo->prepare($sqlChien);
$stmtChien->bindParam(':nom_chien', $nomChien);
$stmtChien->bindParam(':date_naissance_chien', $dateNaissance);
$stmtChien->bindParam('date_inscription', $dateInscription);
$stmtChien->bindParam(':id_utilisateur', $idUtilisateur);
$stmt->bindParam(':id_race', $idRace);
$stmtChien->execute();

$sqlRace = "INSERT INTO race (nom_race, origine,descriptif) VALUES (:nom_race, :origine, :descriptif)";
$stmtRace = $pdo->prepare($sqlRace);
$stmtRace->bindParam(':nom_race', $race);
$stmtRace->bindParam(':origine', $origine);
$stmtRace->bindParam(':descriptif', $descriptif);
$stmtRace->execute();

echo "d)azdka";
}


require_once __DIR__ . '/../templates/inscription.html.php';
