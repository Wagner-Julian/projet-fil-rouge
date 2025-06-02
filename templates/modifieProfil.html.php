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
        <h3>Informations personnelles</h3>
        <input placeholder="Nom" required="" type="text" value="<?= hsc($nom) ?>"/><br /><br />
        <input placeholder="Prénom" required="" type="text" value="<?= hsc($prenom) ?>" /><br /><br />
        <input placeholder="Email" required="" type="email" value="<?= hsc($email)?>" /><br /><br />
        <input placeholder="Nom d'utilisateur" required="" type="text" value="<?= hsc($nomUtilisateur)?>" /><br /><br />
        <button type="submit">💾 Enregistrer</button>
      </form>
    </div>

    <ul>
      <li>Max – Berger Allemand – 3 ans – 2021-01-20</li>
    </ul>
  </main>
  <?php require_once __DIR__ . '/_footer.html.php'; ?>