const { createApp, ref, computed, onMounted } = Vue;

const API = 'http://localhost:8000/api/formations';

const FormationCard = {
    props: { formation: { type: Object, required: true } },
    template: `
        <article class="card">
            <h2>{{ formation.titre }}</h2>
            <p>{{ formation.description }}</p>
            <span class="badge">{{ formation.niveau }}</span>
        </article>
    `
};

createApp({
    components: { FormationCard },

    setup() {
        const formations = ref([]);
        const chargement = ref(true);
        const erreur     = ref('');
        const filtre     = ref('Tous');
        const recherche  = ref('');
        const niveaux    = ['Tous', 'L1', 'L2', 'L3'];

        // États du formulaire d'ajout
        const nouveauTitre       = ref('');
        const nouvelleDescription = ref('');
        const nouveauNiveau      = ref('');
        const envoiEnCours       = ref(false);
        const erreurAjout        = ref('');

        const formationsFiltrees = computed(() => {
            return formations.value.filter(f => {
                const correspondNiveau = filtre.value === 'Tous' || f.niveau === filtre.value;
                const correspondTitre  = f.titre.toLowerCase().includes(recherche.value.trim().toLowerCase());
                return correspondNiveau && correspondTitre;
            });
        });

        const chargerFormations = async () => {
            try {
                const reponse = await fetch(API);
                if (!reponse.ok) throw new Error('HTTP ' + reponse.status);
                formations.value = await reponse.json();
            } catch (e) {
                erreur.value = 'Erreur de chargement : ' + e.message;
            } finally {
                chargement.value = false;
            }
        };

        const ajouterFormation = async () => {
            erreurAjout.value = '';
            envoiEnCours.value = true;

            try {
                const reponse = await fetch(API, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        titre: nouveauTitre.value,
                        description: nouvelleDescription.value,
                        niveau: nouveauNiveau.value
                    })
                });

                if (!reponse.ok) {
                    const data = await reponse.json();
                    throw new Error(data.erreur || 'Erreur lors de l\'ajout');
                }

                const nouvelleFormation = await reponse.json();
                formations.value.push(nouvelleFormation);

                // Réinitialisation des champs
                nouveauTitre.value = '';
                nouvelleDescription.value = '';
                nouveauNiveau.value = '';
            } catch (e) {
                erreurAjout.value = e.message;
            } finally {
                envoiEnCours.value = false;
            }
        };

        onMounted(chargerFormations);

        return { 
            formationsFiltrees, 
            chargement, 
            erreur, 
            filtre, 
            recherche, 
            niveaux,
            nouveauTitre,
            nouvelleDescription,
            nouveauNiveau,
            envoiEnCours,
            erreurAjout,
            ajouterFormation
        };
    }
}).mount('#app');