<?php
session_start();

require_once __DIR__ . '/../include/connection-base-donnees.php';
require_once __DIR__ . '/../include/fonction.php';

if (!isset($_SESSION['id_utilisateur'])) {
    die("Utilisateur non connecté.");
}
$id_utilisateur = $_SESSION['id_utilisateur'];



// Récupérer les cours
$stmt = $pdo->prepare("
    SELECT 
        c.*, 
        t.nom AS nom_tranche, 
        t.age_min_mois, 
        t.age_max_mois, 
        ty.nom_type, 
        u.nom_utilisateur,
        u.email,
        (c.nb_places_cours - COUNT(r.id_reservation)) AS nb_places_cours,
        GROUP_CONCAT(DISTINCT ch.nom_chien SEPARATOR ', ') AS chiens_inscrits
    FROM cours c
    LEFT JOIN tranche_age t ON c.id_tranche = t.id_tranche
    LEFT JOIN type ty ON c.id_type = ty.id_type
    LEFT JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
    LEFT JOIN reservation r ON r.id_cours = c.id_cours
    LEFT JOIN chien ch ON ch.id_chien = r.id_chien
    GROUP BY c.id_cours
    ORDER BY c.date_cours ASC
    LIMIT 3
");



$stmt->execute();
$coursUtilisateur = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($coursUtilisateur as &$cours) {
    $cours['date_cours'] = dateFormatEurope($cours['date_cours']);
}
unset($cours);

$coursCoach = $coursUtilisateur;

// Récupérer les chiens de l'utilisateur connecté
$stmtChiens = $pdo->prepare("
    SELECT id_chien, nom_chien, date_naissance_chien
    FROM chien
    WHERE id_utilisateur = :id_utilisateur
");
$stmtChiens->execute([':id_utilisateur' => $id_utilisateur]);
$chiensUtilisateur = $stmtChiens->fetchAll(PDO::FETCH_ASSOC);

// Si l'utilisateur a cliqué "Réserver"
$messageReservation = '';

if (isset($_POST['id_cours']) && isset($_POST['id_chien'])) {
    $id_cours = (int) $_POST['id_cours'];
    $id_chien = (int) $_POST['id_chien'];

    // Récupérer infos du cours
    $stmtCours = $pdo->prepare("
        SELECT c.date_cours, t.age_min_mois, t.age_max_mois
        FROM cours c
        LEFT JOIN tranche_age t ON c.id_tranche = t.id_tranche
        WHERE c.id_cours = :id_cours
    ");
    $stmtCours->execute([':id_cours' => $id_cours]);
    $cours = $stmtCours->fetch(PDO::FETCH_ASSOC);

    if ($cours) {
        // Récupérer infos du chien (vérifier que c'est bien le chien de l'utilisateur)
        $stmtChien = $pdo->prepare("
            SELECT date_naissance_chien
            FROM chien
            WHERE id_chien = :id_chien AND id_utilisateur = :id_utilisateur
        ");
        $stmtChien->execute([
            ':id_chien' => $id_chien,
            ':id_utilisateur' => $id_utilisateur
        ]);
        $chien = $stmtChien->fetch(PDO::FETCH_ASSOC);

        if ($chien) {
            // Calcul âge du chien en mois à la date du cours
            $dateNaissance = new DateTime($chien['date_naissance_chien']);
            $dateCours = new DateTime($cours['date_cours']);
            $interval = $dateNaissance->diff($dateCours);
            $ageMois = ($interval->y * 12) + $interval->m;

            $ageMin = $cours['age_min_mois'];
            $ageMax = $cours['age_max_mois'];

            $ok = $ageMois >= $ageMin && (is_null($ageMax) || $ageMois <= $ageMax);

        } 
        
    }
    
}

$stmtReservations = $pdo->prepare("
SELECT c.nom_cours, c.date_cours, c.heure_cours, ch.nom_chien
FROM reservation r
INNER JOIN cours c ON c.id_cours = r.id_cours
INNER JOIN chien ch ON ch.id_chien = r.id_chien
WHERE ch.id_utilisateur = :id_utilisateur
ORDER BY c.date_cours ASC
");
$stmtReservations->execute([':id_utilisateur' => $id_utilisateur]);
$reservationsUtilisateur = $stmtReservations->fetchAll(PDO::FETCH_ASSOC);


$stmtUtilisateur = $pdo->prepare(
"SELECT u.nom_utilisateur, u.email, u.nom, u.prenom FROM utilisateur WHERE u.id_utilisateur = :id_utilisateur

");
$stmtUtilisateur->execute([':id_utilisateur' => $id_utilisateur]);
$utilisateurInfos=$stmtUtilisateur->fetch(PDO::FETCH_ASSOC);

require_once __DIR__ . '/../templates/tableauBord.html.php';
