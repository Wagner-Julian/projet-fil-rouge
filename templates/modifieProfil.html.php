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
        <input placeholder="Email" required="" type="email" value="<?= hsc($email)?>" /><br /><br />
        <input placeholder="Nom d'utilisateur" required="" type="text" value="<?= hsc($nomUtilisateur)?>" /><br /><br />
        <h3>🐶 Informations du chien</h3>
        <input placeholder="Nom du chien" required="" type="text" value="<?= hsc($nomChien)?>" /><br /><br />
        <input placeholder="Race" required="" type="text" value="<?= $raceChien?>" /><br /><br />
        <input placeholder="Âge" required="" type="text" value="<?= hsc(ageChien(dateNaissanceChien: $dateNaissanceChien))?>" /><br /><br />
        <input readonly type="date" value="<?= $dateNaissanceChien?>"  /><br /><br />
        <button type="submit">💾 Enregistrer</button>
      </form>
    </div>
    <h3>➕ Ajouter un autre chien</h3>
    <form>
      <input name="nomChien" placeholder="Nom du chien" required="" type="text" /><br /><br />
      <input name="raceChien" placeholder="Race" required="" type="text" /><br /><br />
      <input name="ageChien" placeholder="Âge" required="" type="text" /><br /><br />
      <input name="naissanceChien" required="" type="date" /><br /><br />
      <button type="submit">Ajouter le chien</button>
    </form>
    <h3>🐾 Mes chiens actuels</h3>
    <ul>
      <li>Max – Berger Allemand – 3 ans – 2021-01-20</li>
    </ul>
  </main>
  <?php require_once __DIR__ . '/_footer.html.php'; ?>