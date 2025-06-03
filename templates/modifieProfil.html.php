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
    <h2>✏️ Modifier mon profil</h2>
    <div class="card">
      <form>
        <h2>Informations personnelles</h2>
        <label>Nom</label>
        <input placeholder="Nom" required="" type="text" value="<?= hsc($nom) ?>"/><br /><br />
        <label> Prenom</label>
        <input placeholder="Prénom" required="" type="text" value="<?= hsc($prenom) ?>" /><br /><br />
        <label>Nom utilisateur</label>
        <input placeholder="Nom d'utilisateur" required="" type="text" value="<?= hsc($nomUtilisateur)?>" /><br /><br />
        <label>Email</label>
        <input placeholder="Email" required="" type="email" value="<?= hsc($email)?>" /><br /><br />
        <label> Date d'inscription</label>
        <input type="text" value=" <?php $date=date_create(hsc($dateInscription));echo date_format($date,"d/m/Y") ?>" readonly /><br /><br />
        <button type="submit">💾 Enregistrer</button>
      </form>
    </div>
    <p><strong>Nombre de chiens enregistrés :</strong> <?= $nombreChiens ?></p>



<?php if (!empty($chiens)): ?>
    <?php foreach ($chiens as $c): ?>
        <div class="card-chien">
            <div class="card-chien-info">
                <h4><?= hsc($c['nom_chien']) ?></h4>
                <p><strong>Race :</strong> <?= hsc($c['nom_race']) ?></p>
                <p>
                    <strong> Age :</strong>
                    <?= ageChien($c['date_naissance_chien']) ?>
                </p>
                <p>
                    <strong>Date de naissance :</strong>
                    <?= date('d/m/Y', strtotime($c['date_naissance_chien'])) ?>
                </p>
                <p>
                    <strong>Date d'inscription :</strong>
                    <?= date('d/m/Y', strtotime($c['date_inscription'])) ?>
                </p>
            </div>
            <div class="card-chien-supprimer">
                <a href="modifieProfilChien.php?id_chien=<?= hsc($c['id_chien']) ?>">
                  ✖ Supprimer
                </a>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Vous n’avez aucun chien enregistré.</p>
<?php endif; ?>

  </main>
  <?php require_once __DIR__ . '/_footer.html.php'; ?>