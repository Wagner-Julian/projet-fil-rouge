// ✅ Gestion du bouton "Retour en haut"
const backToTop = document.querySelector(".back-to-top");
const header = document.querySelector("header");

if (backToTop && header) {
  const observer = new IntersectionObserver(
    ([entry]) => {
      if (!entry.isIntersecting) {
        backToTop.classList.add("show");
      } else {
        backToTop.classList.remove("show");
      }
    },
    { threshold: 0 }
  );

  observer.observe(header);
}

// ✅ Vérification email et nom d'utilisateur (champ unique)
const emailInput = document.getElementById('emailInput');
if (emailInput) {
  emailInput.addEventListener('blur', async () => {
    const reponse = await fetch(`verifchamp.php?email=${encodeURIComponent(emailInput.value)}&id=${idUtilisateur}`);
    const data = await reponse.json();

    const errorDiv = document.getElementById('emailErreur');
    if (errorDiv) {
      errorDiv.textContent = data.existe ? "❌ L'email est déjà utilisé." : "";
    }
  });
}

const utilisateurInput = document.getElementById('utilisateurInput');
if (utilisateurInput) {
  utilisateurInput.addEventListener('blur', async () => {
    const reponse = await fetch(`verifchamp.php?nom_utilisateur=${encodeURIComponent(utilisateurInput.value)}&id=${idUtilisateur}`);
    const data = await reponse.json();

    const utilisateurDiv = document.getElementById('utilisateurErreur');
    if (utilisateurDiv) {
      utilisateurDiv.textContent = data.existe ? "❌ Le nom d'utilisateur est déjà utilisé." : "";
    }
  });
}

// ✅ Vérification des mots de passe
const mdpInput = document.getElementById('mot_de_passe');
const confirmationInput = document.getElementById('confirmation_mot_de_passe');
const erreurDiv = document.getElementById('erreur-mdp');
const form = document.getElementById('inscription-form');

function verifierCorrespondance() {
  if (!mdpInput || !confirmationInput || !erreurDiv) return true;
  const mdp = mdpInput.value;
  const confirmation = confirmationInput.value;

  if (confirmation && mdp !== confirmation) {
    erreurDiv.textContent = "⚠️ Les mots de passe ne sont pas identiques.";
    erreurDiv.style.color = "red";
    return false;
  } else if (confirmation && mdp === confirmation) {
    erreurDiv.textContent = "✅ Les mots de passe correspondent.";
    erreurDiv.style.color = "green";
    return true;
  } else {
    erreurDiv.textContent = "";
    return false;
  }
}

if (form && mdpInput && confirmationInput && erreurDiv) {
  confirmationInput.addEventListener('blur', verifierCorrespondance);
  form.addEventListener('submit', function (e) {
    const ok = verifierCorrespondance();
    if (!ok) {
      e.preventDefault();
      erreurDiv.textContent = "❌ Les mots de passe doivent être identiques pour valider.";
      erreurDiv.style.color = "red";
    }
  });
}

// ✅ Message "chien inscrit" (formulaire d'ajout de chien)
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formChien");
  if (form) {
    form.addEventListener("submit", (e) => {
      const nom = form.elements["nom_chien"].value.trim();
      const race = form.elements["race"].value.trim();
      const date = form.elements["date_naissance_chien"].value.trim();

      if (nom && race && date) {
        alert("🐶 Chien inscrit avec succès !");
      }
    });
  }
});

// ✅ Message de succès temporaire (ex: après ajout de cours)
document.addEventListener("DOMContentLoaded", function () {
  const msg = document.getElementById("success-message");
  if (msg) {
    setTimeout(() => {
      msg.style.opacity = "0";
      setTimeout(() => {
        msg.remove();
      }, 500);
    }, 3000);
  }
});


