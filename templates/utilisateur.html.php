<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Club Canin 🐶</title>
  <link href="css/style.css" rel="stylesheet" />
</head>

<body>
  <div id="top"></div>
  <?php require_once __DIR__ . '/_header.html.php'; ?>

  <main>
    <section class="card accueil">
      <h1>🐾 Bienvenue au Club Canin</h1>
      <p>
        Club Canin est une plateforme dédiée à l’éducation canine. Gérez votre profil, ajoutez vos chiens,
        réservez vos séances et suivez vos progrès, le tout en ligne 🐶💻.
      </p>

      <div class="hero-img">
        <img src="ressources/images/xx-groupes-de-chiens-semblant-poser-pour-une-photo-de-classe-91d11cb30.jpg" alt="Présentation du club" />
      </div>

      <div class="features">
        <div class="feature">
          <img src="ressources/images/xx-groupes-de-chiens-semblant-poser-pour-une-photo-de-classe-6bf2f6ee2.jpg" alt="Gérer ses chiens" />
          <h3>Gérez vos chiens</h3>
          <p>Ajoutez, modifiez et suivez les infos de vos compagnons à quatre pattes.</p>
        </div>

        <div class="feature">
          <img src="ressources/images/Decembre_2020_-_nv_frequence_sport_chien.jpg" alt="Cours d'éducation" />
          <h3>Réservez vos cours</h3>
          <p>Choisissez les séances adaptées selon l'âge de votre chien, en quelques clics.</p>
        </div>

        <div class="feature">
          <img src="ressources/images/depositphotos_292995442-stock-ph.webp" alt="Suivi d'évolution" />
          <h3>Suivi personnalisé</h3>
          <p>Accédez à votre tableau de bord et à vos réservations à tout moment.</p>
        </div>
      </div>
      
      <div class="join-button">
      <?php if (isset($_SESSION['id_utilisateur'])): ?>
      <a href="cours.php"><button>📘 Voir nos nouveaux cours </button></a>
      <?php else: ?>
        <a href="inscription.php"><button>📝 Rejoindre le Club </button></a>
      <?php endif; ?>
      </div>
    </section>

    <div class="videoChien">
      <video autoplay muted loop playsinline>
        <source src="ressources/videos/20250323_1148_Rainy Cityscape Bliss_storyboard_01jq19w52nfvfvkaap1a78x5nn.mp4" type="video/mp4">
        Votre navigateur ne supporte pas la lecture vidéo.
      </video>
    </div>
  </main>
  <a href="#top" class="back-to-top" aria-label="Retour en haut">
    ↟
  </a>

  <?php require_once __DIR__ . '/_footer.html.php'; ?>