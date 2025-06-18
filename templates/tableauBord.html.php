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

    <!-- Infos utilisateur -->
    <div class="card">
      <h3>👤 Bienvenue, <?= hsc(($utilisateur['prenom'] ?? '') . ' ' . ($utilisateur['nom'] ?? '')) ?></h3>
      <p><strong>Nom d'utilisateur :</strong> <?= hsc($utilisateur['nom_utilisateur'] ?? '') ?></p>
      <p><strong>Email :</strong> <?= hsc($utilisateur['email'] ?? '') ?></p>
    </div>

    <!-- Infos chiens -->
    <div class="card">
      <h3>🐶 Mon chien</h3>
      <ul>
        <?php if (!empty($chiens)): ?>
          <?php foreach ($chiens as $chien): ?>
            <li>
              <strong><?= hsc($chien['nom_chien']) ?></strong> –
              <?= hsc($chien['nom_race']) ?> –
              <?= ageChien($chien['date_naissance_chien']) ?>
              (<?= date('d/m/Y', strtotime($chien['date_naissance_chien'])) ?>)
            </li>
          <?php endforeach; ?>
        <?php else: ?>
          <li>Aucun chien enregistré.</li>
        <?php endif; ?>
      </ul>
    </div>

    <!-- Cours disponibles -->
    <div class="card">
      <h3>📅 Cours disponibles</h3>
      <?php if (!empty($coursDisponibles)): ?>
        <?php foreach ($coursDisponibles as $cours): ?>
          <div class="card">
            <h3><?= hsc($cours['nom_cours']) ?></h3>
            <p><strong>Âge :</strong> <?= $cours['age_min_mois'] ?><?= is_null($cours['age_max_mois']) ? '+' : '‑' . $cours['age_max_mois'] ?> mois</p>
            <p><strong>Date :</strong> <?= hsc($cours['date_cours']) ?></p>
            <p><strong>Heures :</strong> <?= hsc($cours['heure_cours']) ?></p>
            <p><strong>Places restantes :</strong> <?= max(0, (int)$cours['places_restantes']) ?></p>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p>Aucun cours disponible.</p>
      <?php endif; ?>

      <!-- Réservations -->
      <div class="card">
        <h3>✅ Mes réservations</h3>
        <ul>
          <?php if (!empty($reservations)): ?>
            <?php foreach ($reservations as $resa): ?>
              <li>
                <?= hsc($resa['nom_chien']) ?> –
                <?= hsc($resa['nom_cours']) ?> –
                <?= hsc($resa['date_cours']) ?> –
                <?= hsc($resa['heure_cours']) ?>
              </li>
            <?php endforeach; ?>
          <?php else: ?>
            <li>Aucune réservation pour le moment.</li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </main>
  <a href="#top" class="back-to-top" aria-label="Retour en haut">
    ↟
  </a>

  <?php require_once __DIR__ . '/_footer.html.php'; ?>
</body>

</html>