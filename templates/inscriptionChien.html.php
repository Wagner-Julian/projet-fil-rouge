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
        <h2>Inscription Chien 🐾</h2>
        <form method="post" action="inscriptionChien.php" id="formChien">
            <input name="nom_chien" placeholder="Nom du chien" required="" type="text" /><br /><br />
            <input name="race" placeholder="Race" required="" type="text" /><br /><br />
            <input name="date_naissance_chien" placeholder="Date de naissance Chien (jj/mm/aaaa)" required="" type="text" /><br /><br />
            <button type="submit">Ajouter le chien</button>
        </form>


        </main>

        <?php require_once __DIR__ . '/_footer.html.php'; ?>