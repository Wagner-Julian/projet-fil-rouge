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
            <p><strong>Places :</strong> <?= (int)($cours['nb_places_cours']) ?></p>
            <p><strong>Coach :</strong> <?= htmlspecialchars($cours['nom_utilisateur']) ?></p>

            <!-- Formulaire de réservation -->
            <form method="post" style="margin-top: 10px;">
                <input type="hidden" name="id_cours" value="<?= $cours['id_cours'] ?>">
                <label for="id_chien">Sélectionner un de vos chiens :</label>
                <select name="id_chien" required>
                    <?php foreach ($chiensUtilisateur as $chien): ?>
                        <?php
                            $dateNaissance = new DateTime($chien['date_naissance_chien']);
                            $dateCours = DateTime::createFromFormat('d/m/Y', $cours['date_cours']);
                            $interval = $dateNaissance->diff($dateCours);
                            $ageMois = ($interval->y * 12) + $interval->m;

                            $ageMin = $cours['age_min_mois'];
                            $ageMax = $cours['age_max_mois'];

                            $ok = $ageMois >= $ageMin && (is_null($ageMax) || $ageMois <= $ageMax);
                        ?>
                        <option value="<?= $chien['id_chien'] ?>" <?= $ok ? '' : 'disabled' ?>>
                            <?= htmlspecialchars($chien['nom_chien']) ?> (<?= $ageMois ?> mois)
                            <?= $ok ? '' : ' - âge non compatible' ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <button type="submit">✅ Réserver ce cours</button>
            </form>
        </div>
    <?php endforeach; ?>
<?php endif; ?>
    </select>

</form>


  </main>

  <a href="#top" class="back-to-top" aria-label="Retour en haut">↟</a>
  <?php require_once __DIR__ . '/_footer.html.php'; ?>