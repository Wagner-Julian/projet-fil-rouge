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
    <h2>👤 Mon Profil</h2>

    <?php if (!empty($_SESSION['message'])): ?>
      <div id="success-message" class="message-success">
        <?= $_SESSION['message'];
        unset($_SESSION['message']); ?>
      </div>
    <?php endif; ?>

    <!-- Carte : Informations personnelles -->
    <div class="card profil-card">
      <img
        id="profil-photo"
        src="https://via.placeholder.com/150"
        alt="Photo de profil"
        class="profil-photo" />
      <div class="profil-info">


        <h3>Informations personnelles</h3>
        <p><strong>Nom :</strong> <?= hsc($nom) ?></p>
        <p><strong>Prénom :</strong> <?= hsc($prenom) ?></p>
        <p><strong>Email :</strong> <?= hsc($email) ?></p>
        <p><strong>Nom d'utilisateur :</strong> <?= hsc($nomUtilisateur) ?> </p>
        <label>📸 Modifier la photo :</label><br />
        <input type="file" accept="image/*" onchange="previewImage(event, 'profil-photo')" />
  
      </div>
    </div>


    <!-- Réservations -->
    <div class="card">
  <?php if (empty($reservationsUtilisateur)): ?>
    <p>Vous n'avez pas encore de réservation.</p>
    <?php else: ?>
      <ul>
    <h3>Mes réservations</h3>
        <?php foreach ($reservationsUtilisateur as $resa): ?>
          <?php
          $date = dateFormatEurope($resa['date_cours']);
          $heure = htmlspecialchars($resa['heure_cours']);
          $nomCours = htmlspecialchars($resa['nom_cours']);
          $nomChien = htmlspecialchars($resa['nom_chien']);
        ?>
        <li>
          <?= "$nomCours – $date – $heure – 🐶 $nomChien" ?>
          <a
            href="profil.php?annuler=<?= $resa['id_reservation'] ?>"
            class="annuler-reservation"
            onclick="return confirm('Êtes-vous bien sûr de vous désinscrire du cours ?');"
          >
            Annuler
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  <?php endif; ?>

  
</div>

    <div class="profil-btn">
      <a href="modifieProfil.php">
        <button>✏️ Modifier mon profil</button>
      </a>
    </div>


  </main>
  <a href="#top" class="back-to-top" aria-label="Retour en haut">
    ↟
  </a>

  <?php require_once __DIR__ . '/_footer.html.php'; ?>

