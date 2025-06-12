<?php
session_start();

require_once __DIR__ . '/../include/connection-base-donnees.php';
require_once __DIR__ . '/../include/fonction.php';

$idUtilisateur = $_SESSION['id_utilisateur'] ?? null;
$dateInscription = date('Y-m-d');

if (!$idUtilisateur) {
    die("Utilisateur non connecté.");
}

// Initialiser les variables
$nomChien      = '';
$raceChien     = '';
$dateNaissance = '';
$idChien       = '';

// Récupérer tous les chiens (pour afficher les cards)
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

// Préremplir le formulaire si GET?id_chien
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

// Traitement du formulaire POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idChienPost         = $_POST['id_chien'] ?? null;
    $nomChien            = $_POST['nom_chien'] ?? null;
    $racePost            = $_POST['race'] ?? null;
    $dateNaissanceInput  = $_POST['date_naissance_chien'] ?? null;
    $dateNaissance       = null;

    // Vérification de la date
    if ($dateNaissanceInput && !is_null($dateNaissanceInput)) {
        $dateObj = DateTime::createFromFormat('d/m/Y', $dateNaissanceInput);

        if (!$dateObj || $dateObj->format('d/m/Y') !== $dateNaissanceInput) {
            die("Format de date invalide. Format attendu : jj/mm/aaaa.");
        }

        $dateActuelle = new DateTime();
        if ($dateObj > $dateActuelle) {
            die("La date de naissance ne peut pas être dans le futur.");
        }

        $dateNaissance = $dateObj->format('Y-m-d');
    }

    // Vérifier ou insérer la race
    $sqlCompare = "SELECT id_race FROM race WHERE nom_race = :nom_race";
    $stmtCompare = $pdo->prepare($sqlCompare);
    $stmtCompare->execute([':nom_race' => $racePost]);
    $recherche = $stmtCompare->fetch(PDO::FETCH_ASSOC);

    if ($recherche) {
        $idRace = $recherche['id_race'];
    } else {
        $origine = "Inconnue";
        $descriptif = "Aucune description.";

        $sqlInsertRace = "INSERT INTO race (nom_race, origine, descriptif)
                        VALUES (:nom_race, :origine, :descriptif)";
        $stmtInsert = $pdo->prepare($sqlInsertRace);
        $stmtInsert->execute([
            ':nom_race' => $racePost,
            ':origine' => $origine,
            ':descriptif' => $descriptif
        ]);

        $idRace = $pdo->lastInsertId();
    }

    // Si modification (UPDATE)
if (!empty($idChienPost)) {
    // UPDATE
    $sqlUpdateChien = "UPDATE chien
                    SET nom_chien = :nom_chien,
                        date_naissance_chien = :date_naissance_chien,
                        id_race = :id_race
                    WHERE id_chien = :id_chien
                    AND id_utilisateur = :id_utilisateur";

    $stmtChien = $pdo->prepare($sqlUpdateChien);
    $stmtChien->execute([
        ':nom_chien' => $nomChien,
        ':date_naissance_chien' => $dateNaissance,
        ':id_race' => $idRace,
        ':id_chien' => $idChienPost,
        ':id_utilisateur' => $idUtilisateur
    ]);

    $_SESSION['chien_modifie'] = true;

    // Redirection vers modifieProfilChien.php?id_chien=XX
    header("Location: modifieProfilChien.php");
    exit();

} else {
    // INSERT
    $sqlChien = "INSERT INTO chien (nom_chien, date_inscription, date_naissance_chien, id_utilisateur, id_race)
                VALUES (:nom_chien, :date_inscription, :date_naissance_chien, :id_utilisateur, :id_race)";
    $stmtChien = $pdo->prepare($sqlChien);
    $stmtChien->execute([
        ':nom_chien' => $nomChien,
        ':date_inscription' => $dateInscription,
        ':date_naissance_chien' => $dateNaissance,
        ':id_utilisateur' => $idUtilisateur,
        ':id_race' => $idRace
    ]);

    $_SESSION['chien_inscrit'] = true;

    // Récupérer l'ID du chien inséré
    $idChienInsert = $pdo->lastInsertId();

    // Redirection vers profilChien.php
    header("Location: profilChien.php");
    exit();
}

}

require_once __DIR__ . '/../templates/modifieProfilChien.html.php';
