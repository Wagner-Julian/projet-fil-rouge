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

    <?php if (isset($_SESSION['supprimer_cours'])): ?>
      <p id="success-message" class="message-success">✅ Cours supprimé avec succès.</p>
      <?php unset($_SESSION['supprimer_cours']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['erreur_suppression'])): ?>
      <p id="error-message" class="message-error">❌ <?= $_SESSION['erreur_suppression']; ?></p>
      <?php unset($_SESSION['erreur_suppression']); ?>
    <?php endif; ?>

    <?php if (!empty($_SESSION['message'])): ?>
      <div id="success-message" class="message-success">
        <?= $_SESSION['message'];
        unset($_SESSION['message']); ?>
      </div>
    <?php endif; ?>

    <!-- Formulaire Ajout / Modification -->
    <form method="post" action="coach.php">
      <h3><?= !empty($coursEdit) ? "✏️ Modifier le cours" : "➕ Ajouter un cours" ?></h3>

      <!-- Champ présent dans tous les cas pour éviter le warning -->
      <input type="hidden" name="formCu" value="1">

      <!-- Si on modifie un cours -->
      <?php if (!empty($coursEdit)): ?>
        <input type="hidden" name="id_cours" value="<?= $coursEdit['id_cours'] ?>">
      <?php endif; ?>

      <label>Nom du cours :</label><br>
      <input name="name" placeholder="Nom du cours" required type="text"
        value="<?= !empty($coursEdit) ? hsc($coursEdit['nom_cours']) : '' ?>" /><br><br>

      <label>Type de cours :</label><br>
      <input name="type" placeholder="Type de cours" required type="text"
        value="<?= !empty($coursEdit) ? hsc($coursEdit['nom_type']) :  '' ?>" /><br><br>

      <label>Tranche d’âge :</label><br>
      <select name="id_tranche" required>
        <option value="">-- Sélectionner une tranche --</option>
        <?php foreach ($tranches_age as $tranche): ?>
          <option value="<?= $tranche['id_tranche'] ?>"
            <?= (!empty($coursEdit) && $coursEdit['id_tranche'] == $tranche['id_tranche']) ? 'selected' : '' ?>>
            <?= hsc($tranche['nom']) ?>
          </option>
        <?php endforeach; ?>
      </select><br><br>

      <label>Date du cours :</label><br>
      <input name="date" placeholder="Date (jj/mm/aaaa)" required type="text"
        value="<?= !empty($coursEdit) ? hsc($coursEdit['date_cours']) : '' ?>" /><br><br>

      <label>Heure du cours :</label><br>
      <input type="time" name="time" required style="width: 120px;"
        value="<?= !empty($coursEdit) ? hsc($coursEdit['heure_cours']) : '' ?>" /><br><br>

      <label>Durée :</label><br>
      <select name="duree_cours">
        <option value="30"  <?= (!empty($coursEdit) && $coursEdit['duree_cours'] == 30)  ? 'selected' : '' ?>>30 min</option>
        <option value="60"  <?= (!empty($coursEdit) && $coursEdit['duree_cours'] == 60)  ? 'selected' : '' ?>>1 h</option>
        <option value="90"  <?= (!empty($coursEdit) && $coursEdit['duree_cours'] == 90)  ? 'selected' : '' ?>>1 h 30</option>
        <option value="120" <?= (!empty($coursEdit) && $coursEdit['duree_cours'] == 120) ? 'selected' : '' ?>>2 h</option>
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
          <h4><?= hsc($cours['nom_cours']) ?></h4>
          <p><strong>Tranche d’âge :</strong> <?= hsc($cours['nom_tranche']) ?></p>
          <p><strong>Type de cours :</strong> <?= hsc($cours['nom_type']) ?></p>
          <p><strong>Date :</strong> <?= hsc($cours['date_cours']) ?></p>
          <p><strong>Heure :</strong> <?= hsc($cours['heure_cours']) ?></p>
          <p><strong>Durée :</strong> <?= hsc(formatDuree($cours['duree_cours'])) ?></p>
          <p><strong>Places :</strong> <?= (int)($cours['nb_places_cours']) ?></p>
          <p><strong>Date création :</strong> <?= hsc($cours['date_creation_cours']) ?></p>
          <div class="boutton-supprimer-modifier">
            <a href="coach.php?edit=<?= $cours['id_cours'] ?>">
              <button type="button">🔁 Modifier le cours</button>
            </a>
            <form method="POST" action="coach.php"
                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer le cours <?= hsc($cours['nom_cours']) ?> ?');">
              <input type="hidden" name="supprimer_cours" value="<?= $cours['id_cours'] ?>">
              <input type="hidden" name="formSupprimer" value="2">
              <button type="submit" class="supprimer_cours">❌ Supprimer</button>
            </form>
          </div>
        </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </main>

  <a href="#top" class="back-to-top" aria-label="Retour en haut">↟</a>
  <?php require_once __DIR__ . '/_footer.html.php'; ?>
</body>
</html>
