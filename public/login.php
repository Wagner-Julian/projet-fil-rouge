<?php
session_start();

require_once __DIR__ . '/../include/connection-base-donnees.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $mdp = $_POST['mot_de_passe'] ?? '';

    // Chercher l'utilisateur uniquement par email
    $stmt = $pdo->prepare("SELECT * FROM utilisateur WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier le mot de passe avec password_verify
    if ($user && password_verify($mdp, $user['mot_de_passe'])) {
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
        echo "❌ Identifiants incorrects.";
    }
}

require_once __DIR__ . '/../templates/login.html.php';
