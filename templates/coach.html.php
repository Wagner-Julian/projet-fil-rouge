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
    <h2>⚙️ Coach</h2>

    <?php if (!empty($_SESSION['message'])): ?>
      <div id="success-message" class="message-success">
        <?= $_SESSION['message'];
        unset($_SESSION['message']); ?>
      </div>
    <?php endif; ?>

    <!-- Formulaire Ajout / Modification -->
    <form method="post" action="coach.php">
      <h3><?= !empty($coursEdit) ? "✏️ Modifier le cours" : "➕ Ajouter un cours" ?></h3>

      <!-- Si on modifie un cours -->
      <?php if (!empty($coursEdit)): ?>
        <input type="hidden" name="id_cours" value="<?= $coursEdit['id_cours'] ?>">
      <?php endif; ?>

      <label>Nom du cours :</label><br>
      <input name="name" placeholder="Nom du cours" required type="text"
        value="<?= !empty($coursEdit) ? htmlspecialchars($coursEdit['nom_cours']) : '' ?>" /><br><br>

      <label>Type de cours :</label><br>
      <input name="type" placeholder="Type de cours" required type="text"
        value="<?= !empty($coursEdit) ? htmlspecialchars($coursEdit['nom_type']) : '' ?>" /><br><br>

      <label>Tranche d’âge :</label><br>
      <select name="id_tranche" required>
        <option value="">-- Sélectionner une tranche --</option>
        <?php foreach ($tranches_age as $tranche): ?>
          <option value="<?= $tranche['id_tranche'] ?>"
            <?= (!empty($coursEdit) && $coursEdit['id_tranche'] == $tranche['id_tranche']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($tranche['nom']) ?>
          </option>
        <?php endforeach; ?>
      </select><br><br>

      <label>Date du cours :</label><br>
      <input name="date" placeholder="Date (jj/mm/aaaa)" required type="text"
        value="<?= !empty($coursEdit) ? htmlspecialchars($coursEdit['date_cours']) : '' ?>" /><br><br>

      <label>Heure du cours :</label><br>
      <input type="time" name="time" required style="width: 120px;"
        value="<?= !empty($coursEdit) ? htmlspecialchars($coursEdit['heure_cours']) : '' ?>" /><br><br>

      <label>Durée :</label><br>
      <select name="duree_cours">
        <option value="30" <?= (!empty($coursEdit) && $coursEdit['duree_cours'] == 30) ? 'selected' : '' ?>>30 min</option>
        <option value="60" <?= (!empty($coursEdit) && $coursEdit['duree_cours'] == 60) ? 'selected' : '' ?>>1h</option>
        <option value="90" <?= (!empty($coursEdit) && $coursEdit['duree_cours'] == 90) ? 'selected' : '' ?>>1h30</option>
        <option value="120" <?= (!empty($coursEdit) && $coursEdit['duree_cours'] == 120) ? 'selected' : '' ?>>2h</option>
      </select><br><br>

      <label>Nombre de places :</label><br>
      <input name="places" placeholder="Nombre de places" required type="number"
        value="<?= !empty($coursEdit) ? (int)$coursEdit['nb_places_cours'] : '' ?>" /><br><br>

      <button type="submit">
        <?= !empty($coursEdit) ? "✏️ Modifier le cours" : "➕ Ajouter" ?>
      </button>
    </form>

    <!-- Affichage des cours -->
    <h3>📚 Cours créés</h3>

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
          <p><strong>Places :</strong> <?= (int)($cours['nb_places_cours']) ?></p>
          <p><strong>Date création :</strong> <?= htmlspecialchars($cours['date_creation_cours']) ?></p>

          <a href="coach.php?edit=<?= $cours['id_cours'] ?>">
            <button type="button">🔁 Modifier le cours</button>

          </a>
          <button>❌ Supprimer</button>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>

  </main>

  <a href="#top" class="back-to-top" aria-label="Retour en haut">↟</a>
  <?php require_once __DIR__ . '/_footer.html.php'; ?>