<?php
session_start();

require_once __DIR__ . '/../include/connection-base-donnees.php';
require_once __DIR__ . '/../include/fonction.php';

$idUtilisateur = $_SESSION['id_utilisateur'] ?? null;
$dateInscription = date('Y-m-d');

if (!$idUtilisateur) {
    die("Utilisateur non connecté.");
}

// 1. Initialiser les variables par défaut
$nomChien      = '';
$raceChien     = '';
$dateNaissance = '';
$idChien       = '';

// 2. Récupérer tous les chiens (pour afficher les cards)
$sqlAllChiens = "
    SELECT
    c.id_chien,
    c.nom_chien,
    c.date_naissance_chien,
    r.nom_race
    FROM chien c
    JOIN race r ON c.id_race = r.id_race
    WHERE c.id_utilisateur = :id_utilisateur
    ORDER BY c.id_chien DESC
";
$stmtAll = $pdo->prepare($sqlAllChiens);
$stmtAll->execute([':id_utilisateur' => $idUtilisateur]);
$chiens = $stmtAll->fetchAll(PDO::FETCH_ASSOC);

// 3. Si on veut préremplir le formulaire (GET?id_chien=XX)
if (isset($_GET['id_chien'])) {
    $idChien = $_GET['id_chien'];

    $sqlChien = "
        SELECT c.id_chien, c.nom_chien, c.date_naissance_chien, r.nom_race
        FROM chien c
        JOIN race r ON c.id_race = r.id_race
        WHERE c.id_chien = :id_chien
        AND c.id_utilisateur = :id_utilisateur
    ";
    $stmt = $pdo->prepare($sqlChien);
    $stmt->execute([
        ':id_chien'       => $idChien,
        ':id_utilisateur' => $idUtilisateur
    ]);
    $chien = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($chien) {
        $nomChien      = $chien['nom_chien'];
        $dateNaissance = $chien['date_naissance_chien'];
        $raceChien     = $chien['nom_race'];
    }
}

// 4. Traitement du formulaire (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idChienPost         = $_POST['id_chien'] ?? null;
    $nomChienPost        = $_POST['nom_chien'] ?? null;
    $racePost            = $_POST['race'] ?? null;
    $dateNaissanceInput  = $_POST['date_naissance_chien'] ?? null; // format 'dd/mm/yyyy'
    $dateNaissance       = '';

    // Vérifier format de date
    if ($dateNaissanceInput) {
        $dateObj = DateTime::createFromFormat('d/m/Y', $dateNaissanceInput);
        if (!$dateObj || $dateObj->format('d/m/Y') !== $dateNaissanceInput) {
            die("Format de date invalide (jj/mm/aaaa).");
        }
        if ($dateObj > new DateTime()) {
            die("La date de naissance ne peut pas être dans le futur.");
        }
        $dateNaissance = $dateObj->format('Y-m-d');
    }

    // Vérifier ou insérer la race
    $stmtCompare = $pdo->prepare("SELECT id_race FROM race WHERE nom_race = :nom_race");
    $stmtCompare->execute([':nom_race' => $racePost]);
    $recherche = $stmtCompare->fetch(PDO::FETCH_ASSOC);

    if ($recherche) {
        $idRace = $recherche['id_race'];
    } else {
        $stmtInsertRace = $pdo->prepare("
            INSERT INTO race (nom_race, origine, descriptif)
            VALUES (:nom_race, 'Inconnue', 'Aucune description.')
        ");
        $stmtInsertRace->execute([':nom_race' => $racePost]);
        $idRace = $pdo->lastInsertId();
    }

$idCoursPost = $_POST['id_cours'] ?? null; // Récupère l'id_cours si on modifie

// Vérification ou création du type
$stmt = $pdo->prepare("SELECT id_type FROM type WHERE nom_type = :nom_type");
$stmt->execute(['nom_type' => $typeCours]);
$type = $stmt->fetch(PDO::FETCH_ASSOC);

if ($type) {
    $idType = $type['id_type'];
} else {
    $stmt = $pdo->prepare("INSERT INTO type (nom_type) VALUES (:nom_type)");
    $stmt->execute(['nom_type' => $typeCours]);
    $idType = $pdo->lastInsertId();
}

$idStatus = 1;
$dateCreation = (new DateTime())->format('Y-m-d H:i:s');

if (!empty($idCoursPost)) {
    // 👉 Mise à jour du cours existant
    $stmtUpdate = $pdo->prepare("
        UPDATE cours
        SET nom_cours = :nom,
            date_cours = :date,
            heure_cours = :heure,
            duree_cours = :duree_cours,
            nb_places_cours = :places,
            id_type = :id_type,
            id_status = :id_status,
            id_tranche = :id_tranche
        WHERE id_cours = :id_cours
        AND id_utilisateur = :id_utilisateur
    ");
    $stmtUpdate->execute([
        ':nom'             => $nomCours,
        ':date'            => $dateCours,
        ':heure'           => $heureCours,
        ':duree_cours'     => $dureeCours,
        ':places'          => $places,
        ':id_type'         => $idType,
        ':id_status'       => $idStatus,
        ':id_tranche'      => $idTranche,
        ':id_cours'        => $idCoursPost,
        ':id_utilisateur'  => $id_utilisateur
    ]);

    $_SESSION['message'] = "✅ Le cours a été modifié avec succès !";

} else {
    // 👉 Insertion d'un nouveau cours
    $stmtInsert = $pdo->prepare("
        INSERT INTO cours (
            nom_cours, date_creation_cours, date_cours, heure_cours, duree_cours,
            nb_places_cours, id_utilisateur, id_type, id_status, id_tranche
        ) VALUES (
            :nom, :date_creation_cours, :date, :heure, :duree_cours,
            :places, :id_utilisateur, :id_type, :id_status, :id_tranche
        )
    ");
    $stmtInsert->execute([
        ':nom' => $nomCours,
        ':date_creation_cours' => $dateCreation,
        ':date' => $dateCours,
        ':heure' => $heureCours,
        ':duree_cours' => $dureeCours,
        ':places' => $places,
        ':id_utilisateur' => $id_utilisateur,
        ':id_type' => $idType,
        ':id_status' => $idStatus,
        ':id_tranche' => $idTranche
    ]);

    $_SESSION['message'] = "✅ Le cours a été ajouté avec succès !";
}

// Redirige vers la page coach
header("Location: coach.php");
exit;
}


require_once __DIR__ . '/../templates/modifieProfilChien.html.php';
