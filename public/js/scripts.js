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



// afficher une alerte quand email est identique au champ saisi email

const emailInput = document.getElementById('emailInput');
const utilisateurInput = document.getElementById('utilisateurInput');

emailInput.addEventListener('blur', async () => {
  const reponse = await fetch('verifchamp.php?email=' + encodeURIComponent(emailInput.value));
  const data = await reponse.json();

  const errorDiv = document.getElementById('emailErreur');
  errorDiv.textContent = data.existe ? "❌ L'email est déjà utilisé." : "";
});

utilisateurInput.addEventListener('blur', async () => {
  const reponse = await fetch('verifchamp.php?nom_utilisateur=' + encodeURIComponent(utilisateurInput.value));
  const data = await reponse.json();

  const utilisateurDiv = document.getElementById('utilisateurErreur');
  utilisateurDiv.textContent = data.existe ? "❌ Le nom d'utilisateur est déjà utilisé." : "";
  
});


  const mdpInput = document.getElementById('mot_de_passe');
  const confirmationInput = document.getElementById('confirmation_mot_de_passe');
  const erreurDiv = document.getElementById('erreur-mdp');
  const form = document.getElementById('inscription-form');

  function verifierCorrespondance() {
    const mdp = mdpInput.value;
    const confirmation = confirmationInput.value;

    if (confirmation && mdp !== confirmation) {
      erreurDiv.textContent = "⚠️ Les mots de passe ne sont pas identiques.";
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

  // Vérifie dès que l'utilisateur quitte le champ de confirmation
  confirmationInput.addEventListener('blur', verifierCorrespondance);

  // Vérifie à la soumission du formulaire
  form.addEventListener('submit', function (e) {
    const ok = verifierCorrespondance();
    if (!ok) {
      erreurDiv.style.color = "red";
      erreurDiv.textContent = "❌ Les mots de passe doivent être identiques pour valider.";
      e.preventDefault();
    }
  });


document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("formChien");

  if (!form) return;

  form.addEventListener("submit", (e) => {
    const nom = form.elements["nom_chien"].value.trim();
    const race = form.elements["race"].value.trim();
    const date = form.elements["date_naissance_chien"].value.trim();

    if (nom && race && date) {
      alert("🐶 Chien inscrit avec succès !");
    }
  });
});








