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
    const reponse = await fetch('verifchamp.php?email=' + encodeURIComponent(emailInput.value));
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
    const reponse = await fetch('verifchamp.php?nom_utilisateur=' + encodeURIComponent(utilisateurInput.value));
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



// Récupère le champ input correspondant à la date du cours (id = dateCoursCoach)
const dateInput = document.getElementById('dateCoursCoach');

// Récupère l'élément <p> qui affichera les messages d'erreur (id = messageErreurDate)
const messageErreur = document.getElementById('messageErreurDate');

// Fonction qui vérifie si la date saisie n'est pas déjà dépassée
function verifDateCoursNonDepasse() {

    // Récupère la valeur saisie dans le champ date, en supprimant les espaces inutiles
    const valeur = dateInput.value.trim();

    // Expression régulière pour vérifier que la date est bien au format jj/mm/aaaa
    // ^ début de chaîne
    // (\d{2}) : capture 2 chiffres (jour)
    // \/ : un slash
    // (\d{2}) : capture 2 chiffres (mois)
    // \/ : un slash
    // (\d{4}) : capture 4 chiffres (année)
    // $ fin de chaîne
    const regexDate = /^(\d{2})\/(\d{2})\/(\d{4})$/;

    // Teste si la valeur correspond bien au format attendu
    const match = valeur.match(regexDate);

    // Si la date ne respecte pas le format jj/mm/aaaa
    if (!match) {
        // Affiche un message d'erreur à l'utilisateur
        messageErreur.textContent = "Format de date invalide (jj/mm/aaaa).";
        messageErreur.style.display = "block"; // Affiche le <p> d'erreur
        return; // Sort de la fonction sans continuer
    }

    // Si le format est correct, on récupère les parties de la date
    const jour = parseInt(match[1], 10);   // Le jour (partie 1 du match)
    const mois = parseInt(match[2], 10) - 1; // Le mois (partie 2), -1 car en JS les mois vont de 0 à 11
    const annee = parseInt(match[3], 10);   // L'année (partie 3)

    // Crée un objet Date en JS avec la date choisie par l'utilisateur
    const dateChoisie = new Date(annee, mois, jour);

    // Crée un objet Date pour aujourd'hui
    const aujourdHui = new Date();

    // Met les heures/minutes/secondes à 0 pour comparer uniquement les dates (sans tenir compte de l'heure)
    aujourdHui.setHours(0, 0, 0, 0);
    dateChoisie.setHours(0, 0, 0, 0);

    // Vérifie si la date choisie est avant aujourd'hui (c'est-à-dire dépassée)
    if (dateChoisie < aujourdHui) {
        // Si la date est dépassée, affiche un message d'erreur
        messageErreur.textContent = "La date choisie est déjà passée.";
        messageErreur.style.display = "block";
        // Vide le champ date pour forcer l'utilisateur à en saisir une autre
        dateInput.value = "";
    } else {
        // Si la date est correcte, cache le message d'erreur
        messageErreur.style.display = "none";
    }
}

// Ajoute l'événement seulement si les éléments existent
if (dateInput && messageErreur) {
    dateInput.addEventListener('blur', verifDateCoursNonDepasse);
}

