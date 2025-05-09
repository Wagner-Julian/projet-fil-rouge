// Données globales simulées
const state = {
    courses: [
        { id: 1, name: "École du chiot", age: "0-5 mois", date: "2024-03-15", places: 2 },
        { id: 2, name: "Éducation Junior", age: "6-12 mois", date: "2024-03-16", places: 1 },
        { id: 3, name: "Dressage adulte", age: "1+ ans", date: "2024-03-18", places: 5 },
    ],
    members: [
        { id: 1, owner: "Jean Dupont", dog: "Max", race: "Berger Allemand", age: "3 ans" },
        { id: 2, owner: "Sophie Martin", dog: "Bella", race: "Labrador", age: "1 an" },
    ],
    user: {
        id: 1,
        username: "jdupont",
        role: "user",
        nom: "Jean Dupont",
        email: "jean.dupont@example.com"
    },
    chiens: [
        { nom: "Max", race: "Berger Allemand", age: "3 ans", naissance: "2021-01-20" }
    ]
};

// Fonction pour réserver un cours
function reserverCours(courseId) {
    const course = state.courses.find(c => c.id === courseId);
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

// Fonction pour ajouter un chien (utilisée dans editProfil.html)
function ajouterChien(e) {
    e.preventDefault();
    const form = e.target;
    const nom = form.nomChien.value;
    const race = form.raceChien.value;
    const age = form.ageChien.value;
    const naissance = form.naissanceChien.value;

    const li = document.createElement("li");
    li.textContent = `${nom} – ${race} – ${age} – ${naissance}`;

    document.getElementById("liste-chiens").appendChild(li);
    form.reset();

    alert("Chien ajouté !");
}


  const backToTop = document.querySelector('.back-to-top');
  const header = document.querySelector('header');

  const observer = new IntersectionObserver(
    ([entry]) => {
      if (!entry.isIntersecting) {
        backToTop.classList.add('show');
      } else {
        backToTop.classList.remove('show');
      }
    },
    { threshold: 0 }
  );

  observer.observe(header);
