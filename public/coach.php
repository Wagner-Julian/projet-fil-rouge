<?php
/*****************************************************************
 * coach.php
 * ---------
 * Tableau de bord du coach :
 *   ─ liste ses cours
 *   ─ affiche les chiens inscrits
 *   ─ formulaire d’ajout / modification d’un cours
 *   ─ formulaire de suppression d’un cours
 *
 * Toute la logique « réservation » (inscrire un chien, décrémenter
 * les places) est déportée dans reservation.php.
 *****************************************************************/

session_start();

/*---------------------------------------------------------------
0. CHARGEMENTS COMMUNS
----------------------------------------------------------------*/
require_once __DIR__ . '/../include/connection-base-donnees.php';  // $pdo
require_once __DIR__ . '/../include/fonction.php';                 // helpers (dates, heures, …)

/*---------------------------------------------------------------
1. SÉCURITÉ : l’utilisateur doit être connecté
----------------------------------------------------------------*/
if (!isset($_SESSION['id_utilisateur'])) {
    die('⛔ Utilisateur non connecté.');
}

$id_utilisateur = $_SESSION['id_utilisateur'];

/*---------------------------------------------------------------
2. TRANCHES D’ÂGE (pour le <select>)
----------------------------------------------------------------*/
$stmt = $pdo->query("
    SELECT id_tranche, nom
    FROM tranche_age
    ORDER BY age_min_mois ASC
");
$tranches_age = $stmt->fetchAll(PDO::FETCH_ASSOC);

/*---------------------------------------------------------------
3. MODE ÉDITION : si ?edit=ID, on charge ce cours
----------------------------------------------------------------*/
$idCoursEdit = $_GET['edit'] ?? null;
$coursEdit   = null;

if ($idCoursEdit) {
    $stmt = $pdo->prepare("
        SELECT c.*, t.nom       AS nom_tranche,
            ty.nom_type
        FROM   cours c
        LEFT   JOIN tranche_age t ON t.id_tranche = c.id_tranche
        LEFT   JOIN type        ty ON ty.id_type   = c.id_type
        WHERE  c.id_cours       = :id_cours
        AND  c.id_utilisateur = :id_utilisateur
    ");
    $stmt->execute([
        ':id_cours'       => $idCoursEdit,
        ':id_utilisateur' => $id_utilisateur
    ]);
    $coursEdit = $stmt->fetch(PDO::FETCH_ASSOC);

    /* Petit confort : on repasse la date en JJ/MM/AAAA pour l’input <date> */
    if ($coursEdit) {
        $coursEdit['date_cours'] = dateFormatEurope($coursEdit['date_cours']);
    }
}

/*---------------------------------------------------------------
4. TRAITEMENT DU FORMULAIRE (INSERT / UPDATE d’un cours)
----------------------------------------------------------------*/
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['formCu']) &&                       // petit « token » caché
    $_POST['formCu'] === '1'
) {
    /* 4.1 – récupération des champs */
    $idCoursPost = $_POST['id_cours'] ?? null;       // null → nouveau cours
    $nomCours    = trim($_POST['name']  ?? '');
    $typeCours   = trim($_POST['type']  ?? '');
    $dateCours   = trim($_POST['date']  ?? '');
    $dateCours   = dateFormatUniversel($dateCours);  // JJ/MM/AAAA → YYYY-MM-DD
    $heureCours  = trim($_POST['time']  ?? '');
    $dureeCours  = trim($_POST['duree_cours'] ?? '');
    $places      = (int) ($_POST['places'] ?? 0);    // nb de places max
    $idTranche   = $_POST['id_tranche'] ?? null;

    /* 4.2 – (éventuellement) création d’un nouveau type de cours           */
    $stmt = $pdo->prepare("SELECT id_type FROM type WHERE nom_type = :nom");
    $stmt->execute([':nom' => $typeCours]);
    $type = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($type) {
        $idType = $type['id_type'];
    } else {
        $stmt = $pdo->prepare("INSERT INTO type (nom_type) VALUES (:nom)");
        $stmt->execute([':nom' => $typeCours]);
        $idType = $pdo->lastInsertId();
    }

    $idStatus     = 1;                                 // 1 = « planifié »
    $dateCreation = (new DateTime())->format('Y-m-d H:i:s');

    if ($idCoursPost) {
        /* 4.3 – UPDATE *************************************************** */
        $stmt = $pdo->prepare("
            UPDATE cours
            SET nom_cours       = :nom,
                date_cours      = :date,
                heure_cours     = :heure,
                duree_cours     = :duree,
                nb_places_cours = :places,
                id_type         = :id_type,
                id_status       = :id_status,
                id_tranche      = :id_tranche
            WHERE id_cours       = :id
            AND id_utilisateur = :id_utilisateur
        ");
        $stmt->execute([
            ':nom'            => $nomCours,
            ':date'           => $dateCours,
            ':heure'          => $heureCours,
            ':duree'          => $dureeCours,
            ':places'         => $places,
            ':id_type'        => $idType,
            ':id_status'      => $idStatus,
            ':id_tranche'     => $idTranche,
            ':id'             => $idCoursPost,
            ':id_utilisateur' => $id_utilisateur
        ]);
        $_SESSION['message'] = '✅ Cours modifié.';
    } else {
        /* 4.4 – INSERT *************************************************** */
        $stmt = $pdo->prepare("
            INSERT INTO cours (
                nom_cours, date_creation_cours, date_cours, heure_cours,
                duree_cours, nb_places_cours, id_utilisateur,
                id_type, id_status, id_tranche
            ) VALUES (
                :nom, :date_creation, :date, :heure,
                :duree, :places, :id_utilisateur,
                :id_type, :id_status, :id_tranche
            )
        ");
        $stmt->execute([
            ':nom'            => $nomCours,
            ':date_creation'  => $dateCreation,
            ':date'           => $dateCours,
            ':heure'          => $heureCours,
            ':duree'          => $dureeCours,
            ':places'         => $places,
            ':id_utilisateur' => $id_utilisateur,
            ':id_type'        => $idType,
            ':id_status'      => $idStatus,
            ':id_tranche'     => $idTranche
        ]);
        $_SESSION['message'] = '✅ Cours ajouté.';
    }

    /* 4.5 – redirection pour éviter le double-submit */
    header('Location: coach.php');
    exit;
}

/*---------------------------------------------------------------
5. RÉCUPÉRATION DES CHIENS INSCRITS (groupés par id_cours)
----------------------------------------------------------------*/
$stmt = $pdo->prepare("
    SELECT c.*, co.id_cours, r.etat_reservation
    FROM cours co
    JOIN reservation r ON r.id_cours = co.id_cours
    JOIN chien       c ON c.id_chien = r.id_chien
    WHERE co.id_utilisateur = :id_utilisateur
");
$stmt->execute([':id_utilisateur' => $id_utilisateur]);

$chiensParCours = [];
foreach ($stmt as $row) {
    $chiensParCours[$row['id_cours']][] = $row;
}
/* (❗) Aucune mise à jour du stock ici ! */

/*---------------------------------------------------------------
6. RÉCUPÉRATION DES COURS DU COACH
----------------------------------------------------------------*/
$stmt = $pdo->prepare("
    SELECT c.*, t.nom AS nom_tranche, ty.nom_type
    FROM cours c
    LEFT JOIN tranche_age t ON t.id_tranche = c.id_tranche
    LEFT JOIN type        ty ON ty.id_type   = c.id_type
    WHERE c.id_utilisateur = :id
    ORDER BY c.date_cours ASC
");
$stmt->execute([':id' => $id_utilisateur]);
$coursCoach = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* — formatage date ◦ heure + injection des chiens — */
foreach ($coursCoach as &$cours) {
    $cours['date_cours']  = dateFormatEurope($cours['date_cours']);
    $cours['heure_cours'] = convertirHeure($cours['heure_cours']);
    $cours['chiens']      = $chiensParCours[$cours['id_cours']] ?? [];
}
unset($cours);

/*---------------------------------------------------------------
7. SUPPRESSION D’UN COURS
----------------------------------------------------------------*/
if (
    $_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['formSupprimer'], $_POST['supprimer_cours']) &&
    $_POST['formSupprimer'] === '2'
) {
    $idCoursSuppr = (int) $_POST['supprimer_cours'];

    /* 7.1 – vérif ownership */
    $stmt = $pdo->prepare("
        SELECT 1
        FROM   cours
        WHERE  id_cours = :id
        AND  id_utilisateur = :id_utilisateur
    ");
    $stmt->execute([
        ':id'             => $idCoursSuppr,
        ':id_utilisateur' => $id_utilisateur
    ]);

    if ($stmt->fetchColumn()) {
        /* 7.2 – delete */
        $pdo->prepare("
            DELETE FROM cours WHERE id_cours = :id
        ")->execute([':id' => $idCoursSuppr]);

        $_SESSION['message'] = '🗑️ Cours supprimé.';
    } else {
        $_SESSION['erreur']  = 'Action non autorisée.';
    }

    header('Location: coach.php');
    exit;
}

/*---------------------------------------------------------------
8. AFFICHAGE : on inclut le template HTML
----------------------------------------------------------------*/
require_once __DIR__ . '/../templates/coach.html.php';
