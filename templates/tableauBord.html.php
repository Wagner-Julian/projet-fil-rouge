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
      <h3>👤 Bienvenue, <?= htmlspecialchars($utilisateur['prenom'] . ' ' . $utilisateur['nom']) ?></h3>
      <p><strong>Nom d'utilisateur :</strong> <?= htmlspecialchars($utilisateur['nom_utilisateur']) ?></p>
      <p><strong>Email :</strong> <?= htmlspecialchars($utilisateur['email']) ?></p>
    </div>

    <div class="card">
      <h3>🐶 Mes chiens</h3>
      <?php if (empty($chiens)): ?>
        <p>Aucun chien enregistré.</p>
      <?php else: ?>
        <ul>
          <?php foreach ($chiens as $chien): ?>
            <li><strong><?= hsc($chien['nom_chien']) ?></strong> – <?= hsc($chien['nom_race']) ?> – <?= ageChien($chien['date_naissance_chien']) ?> (<?= hsc($chien['date_naissance_chien']) ?>)</li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>

    <div class="card">
      <h3>📅 Cours disponibles</h3>
      <?php if (empty($coursDisponibles)): ?>
        <p>Aucun cours disponible pour le moment.</p>
      <?php else: ?>
        <?php foreach ($coursDisponibles as $cours): ?>
          <div class="card">
            <h3><?= hsc($cours['nom_cours']) ?></h3>
            <p><strong>Tranche :</strong> <?= hsc($cours['nom_tranche']) ?></p>
            <p><strong>Date :</strong> <?= hsc($cours['date_cours']) ?></p>
            <p><strong>Heure :</strong> <?= hsc($cours['heure_cours']) ?></p>
            <p><strong>Places restantes :</strong> <?= max(0, (int)$cours['nb_places']) ?></p>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>
    </div>

    <div class="card">
      <h3>✅ Mes réservations</h3>
      <?php if (empty($reservations)): ?>
        <p>Aucune réservation.</p>
      <?php else: ?>
        <ul>
          <?php foreach ($reservations as $resa): ?>
            <li><?= hsc($resa['nom_cours']) ?> – <?= hsc($resa['date_cours']) ?> – <?= hsc($resa['heure_cours']) ?></li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>
  </main>
  <a href="#top" class="back-to-top" aria-label="Retour en haut">
    ↟
  </a>

  <?php require_once __DIR__ . '/_footer.html.php'; ?>
</body>

</html>
