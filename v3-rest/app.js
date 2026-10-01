// V3 — Client REST JS
const liste = document.getElementById('liste');

function e(texte) {
    const div = document.createElement('div');
    div.textContent = texte;
    return div.innerHTML;
}

fetch('/api/formations')
    .then(reponse => {
        if (!reponse.ok) throw new Error('HTTP ' + reponse.status);
        return reponse.json();
    })
    .then(formations => {
        liste.innerHTML = formations.map(f => `
            <article class="card">
                <h2><a href="detail.html?id=${f.id}">${e(f.titre)}</a></h2>
                <p>${e(f.description)}</p>
                <span class="badge">${e(f.niveau)}</span>
            </article>
        `).join('');
    })
    .catch(() => {
        liste.innerHTML = '<p class="message">Erreur de chargement.</p>';
    });