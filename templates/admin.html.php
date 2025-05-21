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
    <h2 class="section-title">🛠️ Administration du site</h2>

    <!-- Formulaire pour ajouter un cours -->
    <form class="form-ajout-cours">
      <h3>➕ Ajouter un cours</h3>
      <input type="text" name="name" placeholder="Nom du cours" required><br><br>
      <input type="text" name="age" placeholder="Tranche d'âge (ex : 6-12 mois)" required><br><br>
      <input type="date" name="date" required><br><br>
      <label for="time">Heure du cours :</label><br />
      <input id="time" name="time" type="time" required /><br /><br />
      <input type="number" name="places" placeholder="Nombre de places" required><br><br>
      <button type="submit">Ajouter</button>
    </form>

    <hr>

    <!-- Liste des cours existants -->
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

    <hr>

    <!-- Gestion des droits des utilisateurs -->
    <h3>👥 Gestion des utilisateurs</h3>

    <table class="table-utilisateurs">
      <thead>
        <tr>
          <th>Nom</th>
          <th>Email</th>
          <th>Nom d'utilisateur</th>
          <th>Rôle actuel</th>
          <th>Changer rôle</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>Jean Dupont</td>
          <td class=" elipse"> jean.dupont@example.com</td>
          <td>jdupont</td>
          <td>Utilisateur</td>
          <td>
            <select>
              <option value="user" selected>Utilisateur</option>
              <option value="coach">Coach</option>
              <option value="admin">Admin</option>
            </select>
            <button>✅ Appliquer</button>
          </td>
        </tr>
        <tr>
          <td>Sophie Martin</td>
          <td class="elipse">sophie.martin@example.com</td>
          <td>smartin</td>
          <td>Coach</td>
          <td>
            <select>
              <option value="user">Utilisateur</option>
              <option value="coach" selected>Coach</option>
              <option value="admin">Admin</option>
            </select>
            <button>✅ Appliquer</button>
          </td>
        </tr>
      </tbody>
    </table>
  </main>
</body>
<a href="#top" class="back-to-top" aria-label="Retour en haut">
  ↟
</a>

<?php require_once __DIR__ . '/_footer.html.php'; ?>