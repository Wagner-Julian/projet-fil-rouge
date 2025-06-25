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
  <form method="POST" action="modifieProfil.php">
    <h2>Informations personnelles</h2>

    <?php if (!empty($_SESSION['profil_modifie'])): ?>
      <p id="success-message" class="message-success">✅ Profil modifié avec succès.</p>
      <?php unset($_SESSION['profil_modifie']); ?>
    <?php endif; ?>

    <label>Nom</label>
    <input name="nom" placeholder="Nom" required="" type="text" value="<?= hsc($nom) ?>"/><br /><br />

    <label>Prénom</label>
    <input name="prenom" placeholder="Prénom" required="" type="text" value="<?= hsc($prenom) ?>" /><br /><br />

    <label>Nom utilisateur</label>
    <input name="nom_utilisateur" placeholder="Nom d'utilisateur" required="" type="text" value="<?= hsc($nomUtilisateur) ?>" /><br /><br />

    <label>Email</label>
    <input name="email" placeholder="Email" required="" type="email" value="<?= hsc($email) ?>" /><br /><br />

    <label>Date d'inscription</label>
    <input type="text" value="<?php $date=date_create(hsc($dateInscription));echo date_format($date,"d/m/Y") ?>" readonly /><br /><br />

    <button type="submit">💾 Enregistrer</button>
  </form>
</div>

  </main>
  <?php require_once __DIR__ . '/_footer.html.php'; ?>