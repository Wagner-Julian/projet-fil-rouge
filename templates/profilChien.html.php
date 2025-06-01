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
        <div class="card profil-card">
            <img
                id="chien-photo"
                src="https://via.placeholder.com/150?text=Chien"
                alt="Photo du chien"
                class="chien-photo" />
            <div class="profil-info">
                <h3>🐶 Mon chien</h3>
                <p><strong>Nom :</strong> <?= $nomChien ?> </p>
                <p><strong>Race :</strong> <?= $race ?> </p>
                <p><strong>Âge :</strong> <?= ageChien($dateNaissanceChien) ?> </p>
                <label>📸 Modifier la photo :</label><br />
                <input type="file" accept="image/*" onchange="previewImage(event, 'chien-photo')" />
            </div>
        </div>


    </main>
    <a href="#top" class="back-to-top" aria-label="Retour en haut">
        ↟
    </a>

    <?php require_once __DIR__ . '/_footer.html.php'; ?>