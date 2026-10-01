// V3 — Même code que la V2 : seule l'URL de l'API a changé.
const liste = document.getElementById('liste');

// Échappement minimal côté client (même rôle que htmlspecialchars)
function e(texte) {
    const div = document.createElement('div');
    div.textContent = texte;
    return div.innerHTML;
}

fetch('/api/formations')
    .then(reponse => {
        // Nouveau en V3 : le code HTTP a un sens (200, 404…)
        if (!reponse.ok) throw new Error('HTTP ' + reponse.status);
        return reponse.json();
    })
    .then(formations => {
        // On fabrique du HTML en chaînes de caractères :
        // ça marche… mais imaginez 10 composants et des mises à jour.
        liste.innerHTML = formations.map(f => `
            <article class="card">
                <h2>${e(f.titre)}</h2>
                <p>${e(f.description)}</p>
                <span class="badge">${e(f.niveau)}</span>
            </article>
        `).join('');
    })
    .catch(() => {
        liste.innerHTML = '<p class="message">Erreur de chargement.</p>';
    });
