// V2 — Le navigateur récupère du JSON et construit les cards lui-même.
const liste = document.getElementById('liste');

// Échappement minimal côté client (même rôle que htmlspecialchars)
function e(texte) {
    const div = document.createElement('div');
    div.textContent = texte;
    return div.innerHTML;
}

fetch('api.php?action=list')
    .then(reponse => reponse.json())
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

/* Variante « fragment HTML » : le serveur fabrique encore les cards.
fetch('api.php?action=cards_html')
    .then(r => r.text())
    .then(html => { liste.innerHTML = html; });
*/
