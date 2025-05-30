<?php
session_start();

require_once __DIR__ . '/../include/connection-base-donnees.php';




if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = $_POST['nom'] ?? null;
    $email = $_POST['email'] ?? null;
    $dateInscription = date('Y-m-d') ?? null;
    $nomUtilisateur = $_POST['nom_utilisateur'] ?? null;
    $motDePasse = password_hash($_POST['mot_de_passe'] ?? "", PASSWORD_BCRYPT);
    $nomChien = $_POST['nom_chien'] ?? null;
    $race = $_POST['race'] ?? null;
    $dateNaissanceInput = $_POST['date_naissance'];  // valeur texte 'dd/mm/yyyy'

    $dateNaissance = null;
    $dateActuelle = new DateTime();
    if (isset($dateNaissanceInput)) {
        $dateObj = DateTime::createFromFormat('d/m/Y', $dateNaissanceInput);
        if ($dateObj) {
            $dateNaissance = $dateObj->format('Y-m-d');
        } elseif {
            ($dateNaissance > $today) {
    die("La date de naissance ne peut pas être dans le futur.");
        
    } else {
        die("Date de naissance du chien manquante.");
    }

    $sql = "SELECT COUNT(*) FROM utilisateur WHERE email = :email OR nom_utilisateur = :nom_utilisateur";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':email' => $_POST['email'],
        ':nom_utilisateur' => $_POST['nom_utilisateur']
    ]);
    $existe = $stmt->fetchColumn();

    if ($existe > 0) {
        die("L'email ou le nom d'utilisateur est déjà utilisé.");
    }

    

    $sql = "INSERT INTO utilisateur (nom, email, date_inscription, nom_utilisateur, mot_de_passe, id_role)
        VALUES (:nom, :email, :date_inscription, :nom_utilisateur, :mot_de_passe, :id_role)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':date_inscription', $dateInscription);
    $stmt->bindParam(':nom_utilisateur', $nomUtilisateur);
    $stmt->bindParam(':mot_de_passe', $motDePasse);
    $stmt->bindParam(':id_role', $idRole);
    $idRole = 3;
    $stmt->execute();

    $idUtilisateur = $pdo->lastInsertId();



    $sqlCompare = "SELECT id_race from race WHERE nom_race = :nom_race";
    $stmtCompare = $pdo->prepare($sqlCompare);
    $stmtCompare->bindParam(':nom_race', $race);
    $stmtCompare->execute();
    $recherche = $stmtCompare->fetch(PDO::FETCH_ASSOC);
    if ($recherche) {
        // ✅ Race trouvée → on utilise son ID
        $idRace = $recherche['id_race'];
    } else {
        // Sinon on insère la nouvelle race
        $origine = "Inconnue";
        $descriptif = "Aucune description.";

        $sqlInsertRace = "INSERT INTO race (nom_race, origine, descriptif) VALUES (:nom_race, :origine, :descriptif)";
        $stmtInsert = $pdo->prepare($sqlInsertRace);
        $stmtInsert->bindParam(':nom_race', $race);
        $stmtInsert->bindParam(':origine', $origine);
        $stmtInsert->bindParam(':descriptif', $descriptif);
        $stmtInsert->execute();

        $idRace = $pdo->lastInsertId();
    }



    $sqlChien = "INSERT INTO chien (nom_chien,date_inscription,date_naissance_chien,id_utilisateur, id_race) VALUES (:nom_chien,:date_inscription,:date_naissance_chien, :id_utilisateur, :id_race)";
    $stmtChien = $pdo->prepare($sqlChien);
    $stmtChien->bindParam(':nom_chien', $nomChien);
    $stmtChien->bindParam(':date_naissance_chien', $dateNaissance);
    $stmtChien->bindParam('date_inscription', $dateInscription);
    $stmtChien->bindParam(':id_utilisateur', $idUtilisateur);
    $stmtChien->bindParam(':id_race', $idRace);
    $stmtChien->execute();
}
    



require_once __DIR__ . '/../templates/inscription.html.php';
