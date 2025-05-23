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
    <h2>Inscription 📝</h2>
    
<form action="inscription.php" method="POST" id="inscription-form">
  <input name="nom" placeholder="Nom" required type="text" /><br /><br />
  <input name="email" placeholder="Email" required type="email" /><br /><br />
  <input name="nom_utilisateur" placeholder="Nom utilisateur" required type="text" /><br /><br />
  <input name="mot_de_passe" placeholder="Mot de passe" required type="password" /><br /><br />
  <input name="nom_chien" placeholder="Nom du chien" required type="text" /><br /><br />
  <input name="race" placeholder="Race" required type="text" /><br /><br />
  <input name="date_naissance" placeholder="Date de naissance du chien (jj/mm/aaaa)" required type="text" /><br /><br />
  <button type="submit">S'inscrire</button>
</form>

  <?php var_dump($_POST); ?>
  </main>
  <?php require_once __DIR__ . '/_footer.html.php'; ?>