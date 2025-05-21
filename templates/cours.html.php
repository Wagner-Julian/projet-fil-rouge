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

    <h2>🐕 Nos cours</h2>
    <?php foreach ($cours as $c): ?>
      <div class="card">
        <h3><?= htmlspecialchars($c['nom_type']) ?></h3>
        <p><strong>Âge :</strong> <?= htmlspecialchars($c['tranche_age']) ?></p>
        <p><strong>Date :</strong> <?= htmlspecialchars($c['date_normal']) ?></p>
        <p><strong>Heures :</strong> <?= htmlspecialchars(substr($c['heure_cours'], 0, 5)) ?></p>
        <p><strong>Places restantes :</strong> <?= htmlspecialchars($c['nb_places']) ?></p>
        <button>Réserver</button>
      </div>
    <?php endforeach; ?>

  </main>
  <a href="#top" class="back-to-top" aria-label="Retour en haut">
    ↟
  </a>

  <?php require_once __DIR__ . '/_footer.html.php'; ?>