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

    // Mise à jour ou insertion
    if (!empty($idChienPost)) {
        $stmtUpdate = $pdo->prepare("
            UPDATE chien
            SET nom_chien = :nom_chien,
                date_naissance_chien = :date_naissance_chien,
                id_race = :id_race
            WHERE id_chien = :id_chien
              AND id_utilisateur = :id_utilisateur
        ");
        $stmtUpdate->execute([
            ':nom_chien'            => $nomChienPost,
            ':date_naissance_chien' => $dateNaissance,
            ':id_race'              => $idRace,
            ':id_chien'             => $idChienPost,
            ':id_utilisateur'       => $idUtilisateur
        ]);
    } else {
        $stmtInsert = $pdo->prepare("
            INSERT INTO chien
              (nom_chien, date_inscription, date_naissance_chien, id_utilisateur, id_race)
            VALUES
              (:nom_chien, :date_inscription, :date_naissance_chien, :id_utilisateur, :id_race)
        ");
        $stmtInsert->execute([
            ':nom_chien'            => $nomChienPost,
            ':date_inscription'     => $dateInscription,
            ':date_naissance_chien' => $dateNaissance,
            ':id_utilisateur'       => $idUtilisateur,
            ':id_race'              => $idRace
        ]);
        $idChien = $pdo->lastInsertId();
    }

    $_SESSION['chien_inscrit'] = true;
    header("Location: profilChien.php");
    exit();
}


require_once __DIR__ . '/../templates/modifieProfilChien.html.php';
