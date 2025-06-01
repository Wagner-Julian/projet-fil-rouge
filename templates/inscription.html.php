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
      <input name="prenom" placeholder="Prenom" type="text" /><br /><br />
      <div id="utilisateurErreur" class=" erreur-message"></div>
      <input name="nom_utilisateur" id="utilisateurInput" placeholder="Nom utilisateur" required type="text" /><br /><br />
      <div id="emailErreur" class="erreur-message"></div>
      <input name="email" id="emailInput" placeholder="Email" required type="email" /><br /><br />
      <input name="mot_de_passe" id="mot_de_passe" placeholder="Mot de passe" required type="password" /><br /><br />
      <div id="erreur-mdp" class="erreur-message" style="color: red; margin-bottom: 10px;"></div>
      <input name="confirmation_mot_de_passe" id="confirmation_mot_de_passe" placeholder="Confirmation mot de passe" type="password" /><br /><br />

      <button type="submit">S'inscrire</button>
    </form>



  </main>

  <?php require_once __DIR__ . '/_footer.html.php'; ?>