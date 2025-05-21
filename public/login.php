<?php

session_start();

require_once __DIR__ . '/../include/connection-base-donnees.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $mdp = $_POST['mot_de_passe'] ?? '';

    global $pdo; // Utiliser la connexion PDO définie dans le fichier inclus
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ? AND mot_de_passe = ?");
    $stmt->execute([$email, $mdp]);
    $user = $stmt->fetch();

    if ($user) {
        $_SESSION['id_utilisateur'] = $user['id_utilisateur'];
        $_SESSION['role'] = $user['id_role'];

        // Redirection selon le rôle
        switch ($user['id_role']) {
            case 1:
                header("Location: admin.php");
                break;
            case 2:
                header("Location: coach.php");
                break;
            case 3:
            default:
                header("Location: utilisateur.php");
                break;
        }
        exit();
    } else {
        return "Identifiants incorrects.";
    }
}

require_once __DIR__ . '/../templates/login.html.php';
