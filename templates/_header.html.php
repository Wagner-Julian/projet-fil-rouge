<?php

require_once __DIR__ . '/../include/connection-base-donnees.php';

if (isset($_SESSION['id_utilisateur'])) {

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM chien WHERE id_utilisateur = :id");
    $stmt->execute([':id' => $_SESSION['id_utilisateur']]);
    $aUnChien = $stmt->fetchColumn() > 0;
}
?>

<header>
  <div class="header-logo">
    <a href="index.php#top">
      <img src="ressources/images/image logo club canin.png" alt="Logo Club Canin" />
    </a>
    <h1>Club Canin</h1>
  </div>

  <nav>
    <div class="nav-left">
      <?php if (isset($_SESSION['role']) && ($_SESSION['role'] == 1 || $_SESSION['role'] == 2)): ?>
        <a href="coach.php"><button>Coach</button></a>
      <?php endif ?>
      <a href="tableauBord.php"><button>Tableau de Bord</button></a>
      <a href="cours.php"><button>Cours</button></a>
      <a href="membres.php"><button>Membres</button></a>
    </div>

    <div class ="nav-left-mobile">
      <input type="checkbox" id="mobile-left" hidden>
      <label for="mobile-left" class="menu-mobile-left"> Tableau de Bord ▼</label>
      <div class="menu-deroulant-left">
      <a href="tableauBord.php"><button>Tableau de Bord</button></a>
      <a href="cours.php"><button>Cours</button></a>
      <a href="membres.php"><button>Membres</button></a>
      </div>
    </div>

    <div class="nav-center">
      <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
        <a href="admin.php"><button>👑 Admin</button></a>
      <?php endif; ?>
      <a href="index.php#top"><button>🏠 Accueil</button></a>
    </div>

    <div class= "nav-center-mobile">
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
        <a href="admin.php"><button>👑 Admin</button></a>
      <?php endif; ?>
            <?php if (isset($_SESSION['role']) && ($_SESSION['role'] == 1 || $_SESSION['role'] == 2)): ?>
        <a href="coach.php"><button>Coach</button></a>
      <?php endif ?>
    </div>

    <div class="nav-right">
      <?php if (isset($_SESSION['id_utilisateur'])): ?>
        <a href="profil.php"><button>Profil</button></a>

        <?php if (!empty($aUnChien)): ?>
          <a href="profilChien.php"><button>Profil Chien</button></a>
        <?php else: ?>
          <a href="inscriptionChien.php"><button>Inscription Chien</button></a>
        <?php endif; ?>

        <a href="logout.php"><button>Déconnexion</button></a>
      <?php else: ?>
        <a href="login.php"><button>Connexion</button></a>
        <a href="inscription.php"><button>Inscription</button></a>
      <?php endif; ?>
    </div>

        <div class ="nav-right-mobile">
      <input type="checkbox" id="mobile-right" hidden>
      <label for="mobile-right" class="menu-mobile-right"><?= (isset($_SESSION['id_utilisateur'])) ? 'Profil ▼' : 'Connexion ▼' ?> </label>
      <div class="menu-deroulant-right">
      <?php if (isset($_SESSION['id_utilisateur'])): ?>
        <a href="profil.php"><button>Profil</button></a>

        <?php if (!empty($aUnChien)): ?>
          <a href="profilChien.php"><button>Profil Chien</button></a>
        <?php else: ?>
          <a href="inscriptionChien.php"><button>Inscription Chien</button></a>
        <?php endif; ?>

        <a href="logout.php"><button>Déconnexion</button></a>
      <?php else: ?>
        <a href="login.php"><button>Connexion</button></a>
        <a href="inscription.php"><button>Inscription</button></a>
      <?php endif; ?>
      </div>
        </div>
  </nav>
</header>
