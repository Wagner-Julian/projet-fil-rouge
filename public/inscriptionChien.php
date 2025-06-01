<?php
session_start();

require_once __DIR__ . '/../include/connection-base-donnees.php';
require_once __DIR__ . '/../include/fonction.php';

$idUtilisateur = $_SESSION['id_utilisateur'] ?? null;
$dateInscription = date('Y-m-d');

if (!$idUtilisateur) {
    die("Utilisateur non connecté.");
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomChien = $_POST['nom_chien'] ?? null;
    $race = $_POST['race'] ?? null;
    $dateNaissanceInput = $_POST['date_naissance_chien'] ?? null; // format 'dd/mm/yyyy'
    $dateNaissance = null;

    // 🔒 Vérification de la date
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


    // 🔍 Vérifier ou insérer la race
    $sqlCompare = "SELECT id_race FROM race WHERE nom_race = :nom_race";
    $stmtCompare = $pdo->prepare($sqlCompare);
    $stmtCompare->execute([':nom_race' => $race]);
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
            ':nom_race' => $race,
            ':origine' => $origine,
            ':descriptif' => $descriptif
        ]);

        $idRace = $pdo->lastInsertId();
    }

    // ✅ Insertion du chien

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
    // Redirection vers le profil du chien
    $_SESSION['chien_inscrit'] = true;
    header("Location: profilChien.php");
    exit();
}



require_once __DIR__ . '/../templates/inscriptionChien.html.php';
