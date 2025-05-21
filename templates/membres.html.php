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
    <div class="card">
      <h3>Jean Dupont</h3>
      <p><strong>Chien :</strong> Max</p>
      <p><strong>Race :</strong> Berger Allemand</p>
      <p><strong>Âge :</strong> 3 ans</p>
    </div>
    <div class="card">
      <h3>Sophie Martin</h3>
      <p><strong>Chien :</strong> Bella</p>
      <p><strong>Race :</strong> Labrador</p>
      <p><strong>Âge :</strong> 1 an</p>
    </div>
  </main>
  <a href="#top" class="back-to-top" aria-label="Retour en haut">
    ↟
  </a>

  <?php require_once __DIR__ . '/_footer.html.php'; ?>