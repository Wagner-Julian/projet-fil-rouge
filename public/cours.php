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
    SELECT c.*, t.nom AS nom_tranche, t.age_min_mois, t.age_max_mois, ty.nom_type, u.nom_utilisateur
    FROM cours c
    LEFT JOIN tranche_age t ON c.id_tranche = t.id_tranche
    LEFT JOIN type ty ON c.id_type = ty.id_type
    LEFT JOIN utilisateur u ON c.id_utilisateur = u.id_utilisateur
    ORDER BY c.date_cours ASC
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

            if ($ok) {
                // Insérer la réservation
                $stmtInsert = $pdo->prepare("
                    INSERT INTO reservation (etat_reservation, date_reservation, id_chien, id_cours)
                    VALUES ('Confirmée', CURDATE(), :id_chien, :id_cours)
                ");
                $stmtInsert->execute([
                    ':id_chien' => $id_chien,
                    ':id_cours' => $id_cours
                ]);
                $_SESSION['message'] = "✅ Inscription réussie avec succès !";
            } 
                // Redirige pour éviter un re-submit
    header("Location: cours.php");
    exit;
        } 
    }
}

require_once __DIR__ . '/../templates/cours.html.php';
