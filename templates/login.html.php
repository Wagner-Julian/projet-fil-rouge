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

  
      <?php if (!empty($_SESSION['utilisateur-inscrit'])): ?>
    <p id="success-message" class="message-success">✅ inscription fait avec succès.</p>
    <?php unset($_SESSION['utilisateur-inscrit']); ?>
<?php endif; ?>

    <h2>Connexion 🔐</h2>
    <form method="POST" action="login.php">
      <input name="email" id="emailInput" placeholder="email" type="email" /><br /><br />
      <input name="mot_de_passe" placeholder="Mot de passe" required type="password" /><br /><br />
      <button type="submit">Se connecter</button>
    </form>
  </main>
  <?php require_once __DIR__ . '/_footer.html.php'; ?>