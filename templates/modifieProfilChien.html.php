<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modifier Profil Chien</title>
    <link href="css/style.css" rel="stylesheet" />
    
</head>

<body>
    <div id="top"></div>
    <?php require_once __DIR__ . '/_header.html.php'; ?>
    
    <main>
        
        <h2>✏️ Modifier les informations d’un chien</h2>


        <?php if (isset($_SESSION['supprimer_chien'])): ?>
  <p id="success-message" class="message-success">✅ Chien supprimé avec succès.</p>
  <?php unset($_SESSION['supprimer_chien']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['erreur_suppression'])): ?>
  <p id="error-message" class="message-error">❌ <?= $_SESSION['erreur_suppression']; ?></p>
  <?php unset($_SESSION['erreur_suppression']); ?>
<?php endif; ?>
        
        <div class="card">
            <h2><?= $idChien ? 'Modifier le chien' : '➕ Ajouter un autre chien' ?></h2>
            <form method="post" action="modifieProfilChien.php">
                <input type="hidden" name="id_chien" value="<?= hsc($idChien) ?>" />
                <input  type="hidden" name="formCu" value="1"/>
        
                <label>Nom du chien :</label><br />
                <input
                    name="nom_chien"
                    type="text"
                    required
                    value="<?= hsc($nomChien) ?>" /><br /><br />
        
                <label>Race :</label><br />
                <input
                    name="race"
                    type="text"
                    required
                    value="<?= hsc($raceChien) ?>" /><br /><br />
        
                <label>Date de naissance (jj/mm/aaaa) :</label><br />
                <input
                    name="date_naissance_chien"
                    type="text"
                    required
                    placeholder="jj/mm/aaaa"
                    value="<?= $dateNaissance ? date('d/m/Y', strtotime($dateNaissance)) : '' ?>" /><br /><br />
        
                <button type="submit">
                    <?= $idChien ? '💾 Mettre à jour' : '➕ Ajouter le chien' ?>
                </button>
            </form>
        
        
            <?php if (!empty($_SESSION['chien_modifie'])): ?>
    <p id="success-message" class="message-success">✅ Chien modifié avec succès.</p>
    <?php unset($_SESSION['chien_modifie']); ?>
<?php endif; ?>


        <?php if (!empty($chiens)): ?>
            <?php foreach ($chiens as $c): ?>
                <div class="card-chien">
                    <div class="card-chien-info">
                        <p><strong>Nom du chien :</strong> <?= hsc($c['nom_chien']) ?></p>
                        <p><strong>Race :</strong> <?= hsc($c['nom_race']) ?></p>
                        <p>
                            <strong>Date de naissance :</strong>
                            <?= date('d/m/Y', strtotime($c['date_naissance_chien'])) ?>
                        </p>
                    </div>
                    <div class="card-chien-actions">
                        <a href="modifieProfilChien.php?id_chien=<?= hsc($c['id_chien']) ?>">
                            ✏️ Modifier
                        </a>
                    </div>
                    <form method="POST" action="modifieProfilChien.php" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer le chien <?= hsc($c['nom_chien']) ?> ?');">
                    <input type="hidden" name="supprimer_chien" value="<?= $c['id_chien'] ?>">
                    <input type="hidden" name="formSupprimer" value="2"/>
                    <button type="submit">❌ Supprimer</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Vous n’avez aucun chien enregistré.</p>
        <?php endif; ?>


        </div>

        <hr />



    </main>

    <?php require_once __DIR__ . '/_footer.html.php'; ?>
</body>

</html>