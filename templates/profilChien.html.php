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
    <h2>Informations Chien</h2>
<?php if (!empty($chiens)): ?>
    <?php foreach ($chiens as $index => $chien): ?>
        <div class="card profil-card">
            <img
                id="chien-photo-<?= $index ?>"
                src="https://placehold.co/150x150/smoke/gray?text=Photo+de+Chien&font=playfair-display"
                alt="Photo du chien"
                class="chien-photo" />
            <div class="profil-info">
                <p><strong>Nom :</strong> <?= hsc($chien['nom_chien']) ?> </p>
                <p><strong>Race :</strong> <?= hsc($chien['nom_race']) ?> </p>
                <p><strong>Âge :</strong> <?= ageChien($chien['date_naissance_chien']) ?> </p>
                <p><strong>Date de Naissance :</strong>
                    <?= date('d/m/Y', strtotime($chien['date_naissance_chien'])) ?>
                </p>
                <label>📸 Modifier la photo :</label><br />
                <input type="file" accept="image/*" onchange="previewImage(event, 'chien-photo-<?= $index ?>')" />
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>Vous n’avez aucun chien enregistré.</p>
<?php endif; ?>


        <div class="profil-btn">
            <a href="modifieProfilChien.php">
                <button>✏️ Modifier ou ajouter Chien</button>
            </a>
        </div>


    </main>
    <a href="#top" class="back-to-top" aria-label="Retour en haut">
        ↟
    </a>

    <?php require_once __DIR__ . '/_footer.html.php'; ?>