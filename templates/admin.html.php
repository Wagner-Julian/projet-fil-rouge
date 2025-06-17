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

    
    <hr>
    
    <!-- Gestion des droits des utilisateurs -->
<h3>👥 Gestion des utilisateurs</h3>

<?php if (isset($_SESSION['utilisateur_supprime'])): ?>
  <p id="success-message" class="message-success">✅ Utilisateur supprimé avec succès.</p>
  <?php unset($_SESSION['utilisateur_supprime']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['erreur_suppression'])): ?>
  <p id="error-message" class="message-error">❌ <?= $_SESSION['erreur_suppression']; ?></p>
  <?php unset($_SESSION['erreur_suppression']); ?>
<?php endif; ?>

    <?php if (!empty($_SESSION['role_modifie'])): ?>
      <p id="success-message" class="message-success">✅ Role modifié avec succès.</p>
      <?php unset($_SESSION['role_modifie']); ?>
    <?php endif; ?>

<table class="table-utilisateurs">
  <thead>
    <tr>
      <th>Nom d'utilisateur</th>
      <th>Email</th>
      <th>Rôle actuel</th>
      <th>Changer rôle</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($membres as $id => $membre): ?>
    <tr>
      <td><?= hsc($membre['nom_utilisateur']) ?></td>
      <td class="elipse"><?= hsc($membre['email']) ?></td>
      <td><?= hsc($membre['nom_role']) ?></td>
      <td class="position-boutton">
        <form method="POST" action="admin.php">
          <input type="hidden" name="id_utilisateur" value="<?= $id ?>">
          <select name="nouveau_role">
            <option value="Utilisateur" <?= $membre['nom_role'] === 'Utilisateur' ? 'selected' : '' ?>>Utilisateur</option>
            <option value="Coach" <?= $membre['nom_role'] === 'Coach' ? 'selected' : '' ?>>Coach</option>
            <option value="Admin" <?= $membre['nom_role'] === 'Admin' ? 'selected' : '' ?>>Admin</option>
          </select>
          <button type="submit">✅ Appliquer</button>
        </form>
        
        <!-- Formulaire pour supprimer (séparé) avec confirmation JS -->
  <form method="POST" action="admin.php" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
  <input type="hidden" name="supprimer_utilisateur" value="<?= $id ?>">
  <button type="submit">❌ Supprimer</button>
  </form>
        
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>


    <hr>

  </main>
</body>
<a href="#top" class="back-to-top" aria-label="Retour en haut">
  ↟
</a>

<?php require_once __DIR__ . '/_footer.html.php'; ?>