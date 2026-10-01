// detail.js
const conteneurDetail = document.getElementById('detail');

// Échappement HTML sécurisé
function e(texte) {
    const div = document.createElement('div');
    div.textContent = texte;
    return div.innerHTML;
}

// Récupération de l'ID depuis l'URL (?id=X)
const params = new URLSearchParams(window.location.search);
const id = params.get('id');

if (!id) {
    conteneurDetail.innerHTML = '<p class="message">Aucun identifiant de formation renseigné.</p>';
} else {
    // Appel à l'API backend
    fetch(`api.php?action=get&id=${encodeURIComponent(id)}`)
        .then(async response => {
            if (!response.ok) {
                // Gestion de l'erreur (404 par exemple)
                const errorData = await response.json().catch(() => ({}));
                throw new Error(errorData.erreur || 'Formation introuvable');
            }
            return response.json();
        })
        .then(f => {
            // Affichage de la carte de détail
            conteneurDetail.innerHTML = `
                <article class="card" style="grid-column: 1 / -1;">
                    <h2>${e(f.titre)}</h2>
                    <p>${e(f.description)}</p>
                    <span class="badge">${e(f.niveau)}</span>
                    <p style="margin-top: 1rem; color: #6e7385; font-size: 0.85rem;">
                        Identifiant : ${f.id}
                    </p>
                </article>
            `;
        })
        .catch(err => {
            conteneurDetail.innerHTML = `<p class="message">${e(err.message)}</p>`;
        });
}