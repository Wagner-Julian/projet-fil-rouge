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
    <h2>📊 Tableau de bord</h2>

    <div class="card">
      <h3>👤 Bienvenue, <?= hsc($utilisateurInfos['prenom']) ?> <?= hsc($utilisateurInfos['nom']) ?></h3>
      <p><strong>Nom d'utilisateur :</strong> <?= hsc($utilisateurInfos['nom_utilisateur']) ?> </p>
      <p><strong>Email :</strong> <?= hsc($utilisateurInfos['email']) ?></p>
    </div>

    <h2>🐕 Nos derniers cours</h2>

    <?php if (!empty($_SESSION['message'])): ?>
      <div id="success-message" class="message-success">
        <?= $_SESSION['message'];
        unset($_SESSION['message']); ?>
      </div>
    <?php endif; ?>

    <?php if (empty($coursCoach)): ?>
      <p>Aucun cours créé pour le moment.</p>
    <?php else: ?>
<?php foreach ($coursCoach as $cours): ?>
  <div class="card">
    <h4><?= htmlspecialchars($cours['nom_cours']) ?></h4>
    <p><strong>Tranche d’âge :</strong> <?= htmlspecialchars($cours['nom_tranche']) ?></p>
    <p><strong>Type de cours :</strong> <?= htmlspecialchars($cours['nom_type']) ?></p>
    <p><strong>Date :</strong> <?= htmlspecialchars($cours['date_cours']) ?></p>
    <p><strong>Heure :</strong> <?= htmlspecialchars($cours['heure_cours']) ?></p>
    <p><strong>Durée :</strong> <?= htmlspecialchars(formatDuree($cours['duree_cours'])) ?></p>
    <p><strong>Places restantes :</strong> <?= max(0, (int)$cours['nb_places_cours']) ?></p>
    <p><strong>Coach :</strong> <?= htmlspecialchars($cours['nom_utilisateur']) ?></p>
    <p><strong>Chien Inscrit : </strong> <?= htmlspecialchars($cours['chiens_inscrits'] ?? 'Aucun') ?> </p>

    <?php if ((int)$cours['nb_places_cours'] <= 0): ?>
      <p class="message-erreur">❌ Plus de places disponibles</p>
    <?php endif; ?>
  </div>
<?php endforeach; ?>
<?php endif; ?>




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
  </main>

  <a href="#top" class="back-to-top" aria-label="Retour en haut">↟</a>
  <?php require_once __DIR__ . '/_footer.html.php'; ?>
</body>

</html>
