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
                <?php
                /* ----------------------------------------------------------
       1) Cherche la photo du chien (ou prend un placeholder)
    ---------------------------------------------------------- */
                $photoChien = 'https://placehold.co/150x150/smoke/gray?text=Photo+de+Chien&font=playfair-display';
                foreach (['jpg', 'jpeg', 'png', 'gif', 'webp'] as $ext) {
                    $file = 'chien-' . $chien['id_chien'] . '.' . $ext;    // ex. chien-7.jpg
                    if (is_file(UPLOAD_DISK . $file)) {                // UPLOAD_DISK vient de config.php
                        $photoChien = UPLOAD_URL . $file;              // URL publique
                        break;
                    }
                }
                ?>
                <!-- --------------------------------------------------------
         2) Carte d’un chien + formulaire d’upload dédié
    --------------------------------------------------------- -->
                <div class="card profil-card">
                    <img id="chien-photo-<?= $index ?>"
                        src="<?= hsc($photoChien) ?>"
                        alt="Photo de <?= hsc($chien['nom_chien']) ?>"
                        class="chien-photo" />

                    <div class="profil-info">
                        <p><strong>Nom :</strong> <?= hsc($chien['nom_chien']) ?></p>
                        <p><strong>Race :</strong> <?= hsc($chien['nom_race']) ?></p>
                        <p><strong>Âge :</strong> <?= ageChien($chien['date_naissance_chien']) ?></p>
                        <p><strong>Date de naissance :</strong>
                            <?= date('d/m/Y', strtotime($chien['date_naissance_chien'])) ?></p>

                        <!-- formulaire = 1 chien = 1 photo -->
                        <form action="traitementImageChien.php"
                            method="post"
                            enctype="multipart/form-data"
                            class="upload-chien">
                            <!-- on indique quel chien on modifie -->
                            <input type="hidden" name="id_chien" value="<?= $chien['id_chien'] ?>">
                            <label>📸 Modifier la photo :</label><br>
                            <input type="file" name="chien_image" accept="image/*" required>
                            <button type="submit" name="update_chien_photo">Mettre à jour</button>
                        </form>
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