<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Club Canin 🐶</title>
  <link href="css/style.css" rel="stylesheet" />
  <style>
    .crop-area {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 200px;
      height: 200px;
      cursor: move;
    }

    .crop-wrapper {
      width: 100%;
      height: 100%;
      border: 3px dashed tomato;
      border-radius: 50%;
      position: relative;
      box-sizing: border-box;
    }

    .image-container {
      position: relative;
      width: fit-content;
      height: fit-content;
    }

    .resize-handle {
      position: absolute;
      width: 20px;
      height: 20px;
      background-color: red;
      bottom: -10px;
      right: -10px;
      cursor: nwse-resize;
      border-radius: 4px;
    }

    #result-circle {
      border-radius: 50%;
    }
  </style>
</head>

<body>
  <div id="top"></div>
  <?php require_once __DIR__ . '/_header.html.php'; ?>

  <main>
    <h2>👤 Mon Profil</h2>

    <?php if (!empty($_SESSION['message'])): ?>
      <div id="success-message" class="message-success">
        <?= $_SESSION['message'];
        unset($_SESSION['message']); ?>
      </div>
    <?php endif; ?>

    <div class="card profil-card">
      <?php
      $idUtilisateur = $_SESSION['id_utilisateur'] ?? null;
      $exts = ["jpg", "jpeg", "png", "gif", "webp"];
      $imageProfil = 'https://placehold.co/150x150/smoke/grey?text=Photo+de+profil&font=playfair-display';
      foreach ($exts as $ext) {
        $file = "profil-$idUtilisateur.$ext";
        if (is_file(UPLOAD_DISK . $file)) {
          $imageProfil = UPLOAD_URL . $file;
          break;
        }
      }
      ?>

      <img id="profil-photo" src="<?= $imageProfil ?>" alt="Photo de profil" class="profil-photo" />
      <canvas id="result-circle" width="156" height="156" style="display: none;"></canvas>

      <div class="profil-info">
        <h3>Informations personnelles</h3>
        <p><strong>Nom :</strong> <?= hsc($nom) ?></p>
        <p><strong>Prénom :</strong> <?= hsc($prenom) ?></p>
        <p><strong>Email :</strong> <?= hsc($email) ?></p>
        <p><strong>Nom d'utilisateur :</strong> <?= hsc($nomUtilisateur) ?> </p>

        <form id="profil-form" action="traitementImage.php" method="post" enctype="multipart/form-data">
          <label>📸 Modifier la photo :</label><br />
          <input id="imageUpload" type="file" name="user_image" accept="image/*" required />
          <button type="submit" name="update_photo">📤 Envoyer sans découpe</button>
          <button type="button" id="crop-button">✂️ Découper et envoyer</button>
        </form>

        <div class="image-container">
          <img id="imagePreview" src="" alt="Aperçu de l'image" style="max-width: 100%; display: none;">
          <div id="crop-area" class="crop-area" style="display:none;">
            <div class="crop-wrapper">
              <div class="resize-handle"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Réservations -->
    <div class="card">
      <h3>Mes réservations</h3>
      <?php if (empty($reservationsUtilisateur)): ?>
        <p>Vous n'avez pas encore de réservation.</p>
      <?php else: ?>
        <ul class="liste-reservations">
          <?php foreach ($reservationsUtilisateur as $resa): ?>
            <?php
            $date = dateFormatEurope($resa['date_cours']);
            $heure = convertirHeure($resa['heure_cours']);
            $nomCours = htmlspecialchars($resa['nom_cours']);
            $nomChien = htmlspecialchars($resa['nom_chien']);
            ?>
            <li class="reservation-item">
              <?= "$nomCours – $date – $heure – 🐶 $nomChien" ?>
              <span class="card-chien-supprimer">
                <a href="profil.php?annuler=<?= $resa['id_reservation'] ?>"
                  onclick="return confirm('Êtes-vous bien sûr de vous désinscrire du cours ?');">
                  ❌ Annuler
                </a>
              </span>
            </li>
          <?php endforeach; ?>
        </ul>
      <?php endif; ?>
    </div>

    <div class="profil-btn">
      <a href="modifieProfil.php"><button>✏️ Modifier mon profil</button></a>
    </div>
  </main>

  <a href="#top" class="back-to-top" aria-label="Retour en haut">↟</a>
  <?php require_once __DIR__ . '/_footer.html.php'; ?>

  <!-- JavaScript découpe et soumission -->
  <script>
    const imageAvatar = document.getElementById('profil-photo');
    const imagePreview = document.getElementById('imagePreview');
    const imageInput = document.getElementById('imageUpload');
    const cropArea = document.getElementById('crop-area');
    const resizeHandle = cropArea.querySelector('.resize-handle');
    const cropButton = document.getElementById('crop-button');
    const canvasCircle = document.getElementById('result-circle');
    const form = document.getElementById('profil-form');

    let isDragging = false, isResizing = false;
    let startX, startY, startWidth;

    imageInput.addEventListener('change', function () {
      const file = this.files[0];
      if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
          imagePreview.src = e.target.result;
          imagePreview.style.display = 'block';
          cropArea.style.display = 'block';
        }
        reader.readAsDataURL(file);
      }
    });

    cropArea.addEventListener('mousedown', (e) => {
      if (e.target === resizeHandle) return;
      isDragging = true;
      startX = e.clientX - cropArea.offsetLeft;
      startY = e.clientY - cropArea.offsetTop;
    });

    document.addEventListener('mousemove', (e) => {
      if (isDragging) {
        const x = e.clientX - startX;
        const y = e.clientY - startY;
        cropArea.style.left = `${x}px`;
        cropArea.style.top = `${y}px`;
      }
      if (isResizing) {
        const dx = e.clientX - startX;
        const newSize = Math.max(50, startWidth + dx);
        cropArea.style.width = `${newSize}px`;
        cropArea.style.height = `${newSize}px`;
      }
    });

    document.addEventListener('mouseup', () => {
      isDragging = false;
      isResizing = false;
    });

    resizeHandle.addEventListener('mousedown', (e) => {
      e.stopPropagation();
      isResizing = true;
      startX = e.clientX;
      startWidth = cropArea.offsetWidth;
    });

    cropButton.addEventListener('click', () => {
      const cropRect = cropArea.getBoundingClientRect();
      const imageRect = imagePreview.getBoundingClientRect();

      const scaleX = imagePreview.naturalWidth / imageRect.width;
      const scaleY = imagePreview.naturalHeight / imageRect.height;

      const x = (cropRect.left - imageRect.left) * scaleX;
      const y = (cropRect.top - imageRect.top) * scaleY;
      const size = cropRect.width * scaleX;

      const ctx = canvasCircle.getContext('2d');
      ctx.clearRect(0, 0, 156, 156);
      ctx.save();
      ctx.beginPath();
      ctx.arc(78, 78, 78, 0, Math.PI * 2);
      ctx.clip();
      ctx.drawImage(imagePreview, x, y, size, size, 0, 0, 156, 156);
      ctx.restore();

      canvasCircle.style.display = 'block';
      imageAvatar.style.display = 'none';

      // 🔁 Convertir le canvas en blob et soumettre comme fichier
      canvasCircle.toBlob(function (blob) {
        const file = new File([blob], "decoupe.webp", { type: "image/webp" });
        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        imageInput.files = dataTransfer.files;

        form.submit();
      }, 'image/webp');
    });
  </script>
</body>

</html>