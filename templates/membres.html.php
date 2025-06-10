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
    <h2>Membres du Club</h2>
<?php foreach ($membres as $membre): ?>
  <div class="card-membre">
    <h3>👤 <?= hsc($membre['nom_utilisateur']) ?></h3>
    <?php foreach ($membre['chiens'] as $chien): ?>
      <div class="chien">
        <p><strong>🐶 <?= hsc($chien['nom_chien']) ?></strong></p>
        <p><strong>Race :</strong> <?= hsc($chien['nom_race']) ?></p>
        <p><strong>Âge :</strong> <?= ageChien($chien['date_naissance']) ?></p>
      </div>
      <hr />
    <?php endforeach; ?>
  </div>
<?php endforeach; ?>

  </main>
  <a href="#top" class="back-to-top" aria-label="Retour en haut">
    ↟
  </a>

  <?php require_once __DIR__ . '/_footer.html.php'; ?>