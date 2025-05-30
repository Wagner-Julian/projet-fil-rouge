

// Fonction pour réserver un cours
function reserverCours(courseId) {
  const course = state.courses.find((c) => c.id === courseId);
  if (course && course.places > 0) {
    course.places -= 1;

    const messageDiv = document.createElement("div");
    messageDiv.textContent = "✅ Cours réservé avec succès !";
    messageDiv.style.position = "fixed";
    messageDiv.style.bottom = "20px";
    messageDiv.style.left = "50%";
    messageDiv.style.transform = "translateX(-50%)";
    messageDiv.style.background = "#4caf50";
    messageDiv.style.color = "white";
    messageDiv.style.padding = "10px 20px";
    messageDiv.style.borderRadius = "8px";
    messageDiv.style.boxShadow = "0 2px 6px rgba(0,0,0,0.2)";
    messageDiv.style.zIndex = "1000";
    document.body.appendChild(messageDiv);

    setTimeout(() => {
      messageDiv.remove();
    }, 2000);
  } else {
    alert("Désolé, il n'y a plus de places disponibles pour ce cours.");
  }
}


const backToTop = document.querySelector(".back-to-top");
const header = document.querySelector("header");

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













