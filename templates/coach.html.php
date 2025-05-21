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
    <h2>⚙️ Coach</h2>
    <form>
      <h3>➕ Ajouter un cours</h3>
      <input name="name" placeholder="Nom du cours" required="" type="text" /><br /><br />
      <input name="age" placeholder="Tranche d'âge (ex : 6-12 mois)" required="" type="text" /><br /><br />
      <input name="date" required="" type="date" /><br /><br />
      <label for="time">Heure du cours :</label><br />
      <input id="time" name="time" type="time" required /><br /><br />
      <input name="places" placeholder="Nombre de places" required="" type="number" /><br /><br />
      <button type="submit">Ajouter</button>
    </form>
    <hr />
    <h3>📚 Cours existants</h3>
    <div class="card">
      <h4>École du chiot</h4>
      <p><strong>Âge :</strong> 0-5 mois</p>
      <p><strong>Date :</strong> 2024-03-15</p>
      <p><strong>Heures :</strong> 9h</p>
      <p><strong>Places :</strong> 2</p>
      <button>🔁 Réinitialiser les places</button>
      <button>❌ Supprimer</button>
    </div>
    <div class="card">
      <h4>Éducation Junior</h4>
      <p><strong>Âge :</strong> 6-12 mois</p>
      <p><strong>Date :</strong> 2024-03-16</p>
      <p><strong>Heures :</strong> 9h</p>
      <p><strong>Places :</strong> 1</p>
      <button>🔁 Réinitialiser les places</button>
      <button>❌ Supprimer</button>
    </div>
    <div class="card">
      <h4>Dressage adulte</h4>
      <p><strong>Âge :</strong> 1+ ans</p>
      <p><strong>Date :</strong> 2024-03-18</p>
      <p><strong>Heures :</strong> 9h</p>
      <p><strong>Places :</strong> 5</p>
      <button>🔁 Réinitialiser les places</button>
      <button>❌ Supprimer</button>
    </div>
  </main>
  <a href="#top" class="back-to-top" aria-label="Retour en haut">
    ↟
  </a>

  <?php require_once __DIR__ . '/_footer.html.php'; ?>