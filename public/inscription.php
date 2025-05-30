<?php
session_start();
require_once __DIR__ . '/../include/connection-base-donnees.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? null;
    $email = $_POST['email'] ?? null;
    $dateInscription = date('Y-m-d');
    $nomUtilisateur = $_POST['nom_utilisateur'] ?? null;
    $motDePasse = password_hash($_POST['mot_de_passe'] ?? "", PASSWORD_BCRYPT);
    $nomChien = $_POST['nom_chien'] ?? null;
    $race = $_POST['race'] ?? null;
    $dateNaissanceInput = $_POST['date_naissance'] ?? null; // format 'dd/mm/yyyy'

    // 🔒 Vérification de la date
    if ($dateNaissanceInput) {
        $dateObj = DateTime::createFromFormat('d/m/Y', $dateNaissanceInput);

        if (!$dateObj || $dateObj->format('d/m/Y') !== $dateNaissanceInput) {
            die("Format de date invalide. Format attendu : jj/mm/aaaa.");
        }

        $dateActuelle = new DateTime();
        if ($dateObj > $dateActuelle) {
            die("La date de naissance ne peut pas être dans le futur.");
        }

        $dateNaissance = $dateObj->format('Y-m-d');
    } else {
        die("Date de naissance du chien manquante.");
    }

    // 🔍 Vérifier si l’email ou le nom d'utilisateur existe déjà
    $sql = "SELECT COUNT(*) FROM utilisateur WHERE email = :email OR nom_utilisateur = :nom_utilisateur";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':email' => $email,
        ':nom_utilisateur' => $nomUtilisateur
    ]);

    if ($stmt->fetchColumn() > 0) {
        die("L'email ou le nom d'utilisateur est déjà utilisé.");
    }

    // ✅ Insertion de l'utilisateur
    $sql = "INSERT INTO utilisateur (nom, email, date_inscription, nom_utilisateur, mot_de_passe, id_role)
            VALUES (:nom, :email, :date_inscription, :nom_utilisateur, :mot_de_passe, :id_role)";
    $stmt = $pdo->prepare($sql);
    $idRole = 3; // rôle par défaut
    $stmt->execute([
        ':nom' => $nom,
        ':email' => $email,
        ':date_inscription' => $dateInscription,
        ':nom_utilisateur' => $nomUtilisateur,
        ':mot_de_passe' => $motDePasse,
        ':id_role' => $idRole
    ]);

    $idUtilisateur = $pdo->lastInsertId();

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

    // ✅ Redirection ou confirmation
    echo "Inscription réussie !";
    // header("Location: profil.php"); // décommente si tu veux rediriger après inscription
}

require_once __DIR__ . '/../templates/inscription.html.php';
