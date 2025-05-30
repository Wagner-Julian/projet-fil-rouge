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
    <h2>👤 Mon Profil</h2>

    <!-- Carte : Informations personnelles -->
    <div class="card profil-card">
      <img
        id="profil-photo"
        src="https://via.placeholder.com/150"
        alt="Photo de profil"
        class="profil-photo" />
      <div class="profil-info">


        <h3>Informations personnelles</h3>
        <p><strong>Nom :</strong> <?= $nom ?></p>
        <p><strong>Email :</strong> <?= $email ?></p>
        <p><strong>Nom d'utilisateur :</strong><?= $nomUtilisateur ?> </p>
        <label>📸 Modifier la photo :</label><br />
        <input type="file" accept="image/*" onchange="previewImage(event, 'profil-photo')" />
  
      </div>
    </div>


    <!-- Carte : Informations chien -->
    <div class="card profil-card">
      <img
        id="chien-photo"
        src="https://via.placeholder.com/150?text=Chien"
        alt="Photo du chien"
        class="chien-photo" />
      <div class="profil-info">
        <h3>🐶 Mon chien</h3>
      <p><strong>Nom :</strong> <?= $nomChien?> </p>
        <p><strong>Race :</strong> <?= $raceChien ?> </p>
        <p><strong>Âge :</strong> <?= ageChien($dateNaissanceChien) ?> </p>
        <label>📸 Modifier la photo :</label><br />
        <input type="file" accept="image/*" onchange="previewImage(event, 'chien-photo')" />
      </div>
    </div>

    <!-- Réservations -->
    <div class="card">
      <h3>📅 Mes réservations</h3>
      <ul>
        <li>École du chiot – 2024-03-15 – 9h </li>
        <li>Éducation Junior – 2024-03-16 – 14h </li>
      </ul>
    </div>

    <div class="profil-btn">
      <a href="modifieProfil.php">
        <button>✏️ Modifier mon profil</button>
      </a>
    </div>


  </main>
  <a href="#top" class="back-to-top" aria-label="Retour en haut">
    ↟
  </a>

  <?php require_once __DIR__ . '/_footer.html.php'; ?>
