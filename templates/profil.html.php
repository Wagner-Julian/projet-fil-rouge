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

    <div class="card profil-card">
      <?php
      $idUtilisateur = $_SESSION['id_utilisateur'] ?? null;
      $exts = ["jpg", "jpeg", "png", "gif", "webp"];
      $imageProfil = 'https://placehold.co/150x150/smoke/grey?text=Photo+de+profil&font=playfair-display';
      foreach ($exts as $ext) {
        $file = "profil-$idUtilisateur.$ext";
        if (is_file(UPLOAD_DISK . $file)) {
          $imageProfil = UPLOAD_URL . $file;
          break;
        }
      }
      ?>

      <img id="profil-photo" src="<?= $imageProfil ?>" alt="Photo de profil" class="profil-photo" />
      <canvas id="result-circle" width="156" height="156" style="display: none;"></canvas>

      <div class="profil-info">
        <h3>Informations personnelles</h3>
        <p><strong>Nom :</strong> <?= hsc($nom) ?></p>
        <p><strong>Prénom :</strong> <?= hsc($prenom) ?></p>
        <p><strong>Email :</strong> <?= hsc($email) ?></p>
        <p><strong>Nom d'utilisateur :</strong> <?= hsc($nomUtilisateur) ?> </p>

        <form id="profil-form" action="traitementImage.php" method="post" enctype="multipart/form-data">
          <label>📸 Modifier la photo :</label><br />
          <input id="imageUpload" type="file" name="user_image" accept="image/*" required />
          <button type="submit" name="update_photo">📤 Envoyer sans découpe</button>
          <button type="button" id="crop-button">✂️ Découper et envoyer</button>
        </form>

        <div class="image-container">
          <img id="imagePreview" src="" alt="Aperçu de l'image" style="max-width: 100%; display: none;">
          <div id="crop-area" class="crop-area" style="display:none;">
            <div class="crop-wrapper">
              <div class="resize-handle"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Réservations -->
    <div class="card">
      <h3>Mes réservations</h3>
      <?php if (empty($reservationsUtilisateur)): ?>
        <p>Vous n'avez pas encore de réservation.</p>
      <?php else: ?>
        <ul class="liste-reservations">
          <?php foreach ($reservationsUtilisateur as $resa): ?>
            <?php
            $date = dateFormatEurope($resa['date_cours']);
            $heure = convertirHeure($resa['heure_cours']);
            $nomCours = htmlspecialchars($resa['nom_cours']);
            $nomChien = htmlspecialchars($resa['nom_chien']);
            ?>
            <li class="reservation-item">
              <?= "$nomCours – $date – $heure – 🐶 $nomChien" ?>
              <span class="card-chien-supprimer">
                <a href="profil.php?annuler=<?= $resa['id_reservation'] ?>"
                  onclick="return confirm('Êtes-vous bien sûr de vous désinscrire du cours ?');">
                  ❌ Annuler
                </a>
              </span>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>

    <div class="profil-btn">
      <a href="modifieProfil.php"><button>✏️ Modifier mon profil</button></a>
    </div>
  </main>

  <a href="#top" class="back-to-top" aria-label="Retour en haut">↟</a>
  <script src="js/scriptsProfil.js" defer></script>
  <?php require_once __DIR__ . '/_footer.html.php'; ?>

</body>

</html>