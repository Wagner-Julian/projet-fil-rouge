<?php
/*****************************************************************
 * reservation.php
 * ---------------
 * Action appelée par le formulaire « Réserver » du coach.
 * Étapes :
 *   1. Vérifier la session (coach connecté)
 *   2. Vérifier que le chien appartient au coach
 *   3. Vérifier qu’il reste au moins 1 place (optionnel mais conseillé)
 *   4. INSERT dans reservation
 *   5. UPDATE cours (nb_places_cours - 1)
 *   6. Redirection vers coach.php
 *****************************************************************/

session_start();
require_once __DIR__ . '/../include/connection-base-donnees.php';

if (!isset($_SESSION['id_utilisateur'])) {
    die('⛔ Utilisateur non connecté.');
}
$id_utilisateur = $_SESSION['id_utilisateur'];

/*---------------------------------------------------------------
0. Récupération des champs POST
----------------------------------------------------------------*/
$idCours = (int) ($_POST['id_cours']  ?? 0);  // cours à réserver
$idChien = (int) ($_POST['id_chien']  ?? 0);  // chien qui s’inscrit

if (!$idCours || !$idChien) {
    $_SESSION['erreur'] = 'Données manquantes.';
    header('Location: coach.php'); exit;
}

/*---------------------------------------------------------------
1. Vérifier que le chien appartient bien au coach
----------------------------------------------------------------*/
$stmt = $pdo->prepare("
    SELECT 1
    FROM   chien
    WHERE  id_chien       = :id_chien
    AND  id_utilisateur = :id_utilisateur
");
$stmt->execute([
    ':id_chien'       => $idChien,
    ':id_utilisateur' => $id_utilisateur
]);

if (!$stmt->fetchColumn()) {
    $_SESSION['erreur'] = '🐶 Ce chien ne vous appartient pas.';
    header('Location: coach.php'); exit;
}

/*---------------------------------------------------------------
2. (Optionnel) Vérifier qu’il reste une place
----------------------------------------------------------------*/
$stmt = $pdo->prepare("
    SELECT nb_places_cours
    FROM   cours
    WHERE  id_cours = :id_cours
");
$stmt->execute([':id_cours' => $idCours]);
$placesRestantes = $stmt->fetchColumn();

if ($placesRestantes <= 0) {
    $_SESSION['erreur'] = '❌ Plus de place dans ce cours.';
    header('Location: coach.php'); exit;
}

/*---------------------------------------------------------------
3. INSERT dans reservation
----------------------------------------------------------------*/
$pdo->prepare("
    INSERT INTO reservation (id_cours, id_chien, date_reservation)
    VALUES (:id_cours, :id_chien, NOW())
")->execute([
    ':id_cours' => $idCours,
    ':id_chien' => $idChien
]);

/*---------------------------------------------------------------
4. Décrémenter les places restantes du cours
----------------------------------------------------------------*/
$pdo->prepare("
    UPDATE cours
    SET nb_places_cours = nb_places_cours - 1
    WHERE id_cours = :id_cours
")->execute([':id_cours' => $idCours]);

/*---------------------------------------------------------------
5. Message + redirection
----------------------------------------------------------------*/
$_SESSION['message'] = '✅ Réservation enregistrée !';
header('Location: coach.php');
