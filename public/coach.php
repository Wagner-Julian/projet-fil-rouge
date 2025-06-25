<?php
session_start();

// Connexion et fonctions
require_once __DIR__ . '/../include/connection-base-donnees.php';
require_once __DIR__ . '/../include/fonction.php';

// ✅ Vérification utilisateur
if (!isset($_SESSION['id_utilisateur'])) {
    die("Utilisateur non connecté.");
}
$id_utilisateur = $_SESSION['id_utilisateur'];

// ✅ Récupération des tranches d'âge pour le select
$stmt = $pdo->query("SELECT id_tranche, nom FROM tranche_age ORDER BY age_min_mois ASC");
$tranches_age = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ Si on est en mode "edit" → on récupère le cours à modifier
$idCoursEdit = $_GET['edit'] ?? null;
$coursEdit = null;

if (!empty($idCoursEdit)) {
    $stmtEdit = $pdo->prepare("
        SELECT c.*, t.nom AS nom_tranche, ty.nom_type
        FROM cours c
        LEFT JOIN tranche_age t ON c.id_tranche = t.id_tranche
        LEFT JOIN type ty ON c.id_type = ty.id_type
        WHERE c.id_cours = :id_cours
        AND c.id_utilisateur = :id_utilisateur
    ");
    $stmtEdit->execute([
        ':id_cours' => $idCoursEdit,
        ':id_utilisateur' => $id_utilisateur
    ]);
    $coursEdit = $stmtEdit->fetch(PDO::FETCH_ASSOC);

    // ✅ On convertit la date en format Europe pour le formulaire
    if ($coursEdit) {
        $coursEdit['date_cours'] = dateFormatEurope($coursEdit['date_cours']);
    }
}

// ✅ Traitement du formulaire
if (    $_SERVER["REQUEST_METHOD"] === "POST"
    && isset($_POST['formCu'])      // ✅ protège l’accès
    && $_POST['formCu'] === '1') 
{

    // Données formulaire
    $idCoursPost = $_POST['id_cours'] ?? null; // sert pour savoir si on UPDATE ou INSERT
    $nomCours = $_POST['name'] ?? '';
    $typeCours = $_POST['type'] ?? '';
    $dateCours = $_POST['date'] ?? '';
    $dateCours = dateFormatUniversel($dateCours); // conversion en Y-m-d
    $heureCours = $_POST['time'] ?? '';
    $dureeCours = $_POST['duree_cours'] ?? '';
    $places = $_POST['places'] ?? 0;
    $idTranche = $_POST['id_tranche'] ?? null;

    // ✅ Gestion du type : vérifier s'il existe, sinon le créer
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

    $idStatus = 1; // par défaut
    $dateCreation = (new DateTime())->format('Y-m-d H:i:s'); // date du jour

    // ✅ Si on MODIFIE un cours existant
    if (!empty($idCoursPost)) {

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
            'nom' => $nomCours,
            'date' => $dateCours,
            'heure' => $heureCours,
            'duree_cours' => $dureeCours,
            'places' => $places,
            'id_type' => $idType,
            'id_status' => $idStatus,
            'id_tranche' => $idTranche,
            'id_cours' => $idCoursPost,
            'id_utilisateur' => $id_utilisateur
        ]);

        $_SESSION['message'] = "✅ Le cours a été modifié avec succès !";

    } else {
        // ✅ Sinon, c'est un NOUVEAU cours
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
            'nom' => $nomCours,
            'date_creation_cours' => $dateCreation,
            'date' => $dateCours,
            'heure' => $heureCours,
            'duree_cours' => $dureeCours,
            'places' => $places,
            'id_utilisateur' => $id_utilisateur,
            'id_type' => $idType,
            'id_status' => $idStatus,
            'id_tranche' => $idTranche
        ]);

        $_SESSION['message'] = "✅ Le cours a été ajouté avec succès !";
    }

    // Redirige pour éviter un re-submit
    header("Location: coach.php");
    exit;
}

// ✅ On récupère TOUS les cours existants du coach
$stmt = $pdo->prepare("
    SELECT c.*, t.nom AS nom_tranche, ty.nom_type
    FROM cours c
    LEFT JOIN tranche_age t ON c.id_tranche = t.id_tranche
    LEFT JOIN type ty ON c.id_type = ty.id_type
    WHERE c.id_utilisateur = :id
    ORDER BY c.date_cours ASC
");
$stmt->execute(['id' => $id_utilisateur]);
$coursCoach = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ✅ On formate les dates pour affichage
foreach ($coursCoach as &$cours) {
    $cours['date_cours'] = dateFormatEurope($cours['date_cours']);
    $cours['heure_cours'] = convertirHeure($cours['heure_cours']);
}
unset($cours); // bonne pratique


if (
    $_SERVER["REQUEST_METHOD"] === "POST" &&
    isset($_POST['supprimer_cours'], $_POST['formSupprimer']) &&
    $_POST['formSupprimer'] === '2'
) {
    $idcoursASupprimer = (int) $_POST['supprimer_cours'];

    $stmt = $pdo->prepare("SELECT id_utilisateur FROM cours WHERE id_cours = :id_cours");
    $stmt->execute(['id_cours' => $idcoursASupprimer]);
    $cours = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$cours || $cours['id_utilisateur'] != $id_utilisateur) {
        $_SESSION['erreur_suppression'] = "Action non autorisée.";
    } else {
        $stmtDeletecours = $pdo->prepare("DELETE FROM cours WHERE id_cours = :id_cours");
        $stmtDeletecours->execute([':id_cours' => $idcoursASupprimer]);

        $_SESSION['supprimer_cours'] = true;
    }

    header("Location: coach.php");
    exit;
}



// On charge le template HTML
require_once __DIR__ . '/../templates/coach.html.php';
