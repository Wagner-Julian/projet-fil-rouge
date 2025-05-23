  <header>

    <div class="header-logo">
      <a href="index.php#top">
        <img src="ressources/images/image logo club canin.png" alt="Logo Club Canin" />
      </a>

      <h1>Club Canin</h1>
    </div>
    <nav>
      <div class="nav-left">
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1 or isset($_SESSION['role']) && $_SESSION['role'] == 2): ?>
          <a href="coach.php"><button>Coach</button></a>
        <?php endif ?>
        <a href="tableauBord.php"><button>Tableau de Bord</button></a>
        <a href="cours.php"><button>Cours</button></a>
        <a href="membres.php"><button>Membres</button></a>

      </div>

      <div class="nav-center">
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] == 1): ?>
          <a href="admin.php"><button>👑 Admin</button></a>
        <?php endif; ?>
        <a href="index.php#top"><button>🏠 Accueil</button></a>

      </div>

      <div class="nav-right">
        <?php if (isset($_SESSION['id_utilisateur'])): ?>
          <a href="profil.php"><button>Profil</button></a>
          <a href="logout.php"><button>Déconnexion</button></a>
        <?php else: ?>
          <a href="login.php"><button>Connexion</button></a>
          <a href="inscription.php"><button>Inscription</button></a>
        <?php endif; ?>
      </div>
    </nav>
  </header>