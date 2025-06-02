<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modifier Profil Chien</title>
    <link href="css/style.css" rel="stylesheet" />
    <style>
        .card-chien {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 16px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }


    </style>
</head>

<body>
    <div id="top"></div>
    <?php require_once __DIR__ . '/_header.html.php'; ?>

    <main>

        <h2>✏️ Modifier les informations d’un chien</h2>

        <?php if (!empty($chiens)): ?>
            <?php foreach ($chiens as $c): ?>
                <div class="card-chien">
                    <div class="card-chien-info">
                        <h4><?= hsc($c['nom_chien']) ?></h4>
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
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Vous n’avez aucun chien enregistré.</p>
        <?php endif; ?>

        <!-- FORMULAIRE DE MODIFICATION PRÉREMPLI (s’il y a un $idChien) -->
        <div class="card">
            <h2><?= $idChien ? 'Modifier le chien' : '➕ Ajouter un autre chien' ?></h2>
            <form method="post" action="modifieProfilChien.php">
                <input type="hidden" name="id_chien" value="<?= hsc($idChien) ?>" />

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
        </div>

        <hr />

        <!-- CARDS POUR TOUS LES CHIENS DE L'UTILISATEUR -->

    </main>

    <?php require_once __DIR__ . '/_footer.html.php'; ?>
</body>

</html>