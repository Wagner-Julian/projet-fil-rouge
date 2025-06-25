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
        <?= $_SESSION['message']; unset($_SESSION['message']); ?>
      </div>
    <?php endif; ?>

    <!-- Carte : Informations personnelles -->
    <div class="card profil-card">
<?php
$idUtilisateur = $_SESSION['id_utilisateur'] ?? null;

/* Extensions possibles à tester */
$exts = ["jpg", "jpeg", "png", "gif", "webp"];

/* Valeur par défaut si aucune image */
$imageProfil = "https://placehold.co/150x150/smoke/grey?text=Photo+de+profil&font=playfair-display";

/* Dossier disque = …/public/ressources/telechargement/ */
$baseDisk = realpath(__DIR__ . "/../public/ressources/telechargement");
$baseUrl  = "/projet-fil-rouge/public/ressources/telechargement";

if ($idUtilisateur && $baseDisk) {
    foreach ($exts as $ext) {
        $file = "profil-$idUtilisateur.$ext";
        if (file_exists("$baseDisk/$file")) {
            $imageProfil = "$baseUrl/$file";   // URL navigateur
            break;
        }
    }
}
?>


<img id="profil-photo" src="<?= $imageProfil ?>" alt="Photo de profil" class="profil-photo" />








      <div class="profil-info">
        <h3>Informations personnelles</h3>
        <p><strong>Nom :</strong> <?= hsc($nom) ?></p>
        <p><strong>Prénom :</strong> <?= hsc($prenom) ?></p>
        <p><strong>Email :</strong> <?= hsc($email) ?></p>
        <p><strong>Nom d'utilisateur :</strong> <?= hsc($nomUtilisateur) ?> </p>

        <form action="traitementImage.php" method="post" enctype="multipart/form-data">
          <label>📸 Modifier la photo :</label><br />
          <input type="file" name="user_image" accept="image/*" required />
          <button type="submit" name="update_photo">Mettre à jour la photo</button>
        </form>
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
                <a href="profil.php?annuler=<?= $resa['id_reservation'] ?>" onclick="return confirm('Êtes-vous bien sûr de vous désinscrire du cours ?');">
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

  <?php require_once __DIR__ . '/_footer.html.php'; ?>
</body>
</html>
