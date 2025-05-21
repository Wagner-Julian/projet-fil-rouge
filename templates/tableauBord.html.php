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
    <h2>📊 Tableau de bord</h2>

    <div class="card">
      <h3>👤 Bienvenue, Jean Dupont</h3>
      <p><strong>Nom d'utilisateur :</strong> jdupont</p>
      <p><strong>Email :</strong> jean.dupont@example.com</p>
    </div>

    <div class="card">
      <h3>🐶 Mon chien</h3>
      <ul>
        <li><strong>Max</strong> – Berger Allemand – 3 ans (2021-01-20)</li>
      </ul>
    </div>

    <div class="card">
      <h3>📅 Cours disponibles</h3>

      <div class="card">
        <h3>École du chiot</h3>
        <p><strong>Âge :</strong> 0-5 mois</p>
        <p><strong>Date :</strong> 2024-03-15</p>
        <p><strong>Heures :</strong> 9h</p>
        <p><strong>Places restantes :</strong> 2</p>
        <button>Réserver</button>
      </div>
      <div class="card">
        <h3>Éducation Junior</h3>
        <p><strong>Âge :</strong> 6-12 mois</p>
        <p><strong>Date :</strong> 2024-03-16</p>
        <p><strong>Heures :</strong> 14h</p>
        <p><strong>Places restantes :</strong> 1</p>
        <button>Réserver</button>
      </div>
      <div class="card">
        <h3>Dressage adulte</h3>
        <p><strong>Âge :</strong> 1+ ans</p>
        <p><strong>Date :</strong> 2024-03-18</p>
        <p><strong>Heures :</strong> 14h</p>
        <p><strong>Places restantes :</strong> 5</p>
        <button>Réserver</button>
      </div>

      <div class="card">
        <h3>✅ Mes réservations</h3>
        <ul>
          <li>École du chiot – 2024-03-15 – 9h </li>
          <li>Éducation Junior – 2024-03-16 – 14h </li>
        </ul>
      </div>
  </main>
  <a href="#top" class="back-to-top" aria-label="Retour en haut">
    ↟
  </a>

  <?php require_once __DIR__ . '/_footer.html.php'; ?>
</body>

</html>