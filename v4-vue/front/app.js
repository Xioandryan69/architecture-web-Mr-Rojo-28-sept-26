// V4 — On décrit l'interface à partir des données ; Vue s'occupe du DOM.
const { createApp, ref, computed, onMounted } = Vue;

const API = 'http://localhost:8000/api/formations';

// Un composant = un morceau d'interface réutilisable
const FormationCard = {
    props: { formation: { type: Object, required: true } },
    template: `
        <article class="card">
            <h2>{{ formation.titre }}</h2>
            <p>{{ formation.description }}</p>
            <span class="badge">{{ formation.niveau }}</span>
        </article>
    `
    // {{ }} échappe automatiquement : plus besoin de fonction e()
};

createApp({
    components: { FormationCard },

    setup() {
        const formations = ref([]);        // état : les données
        const chargement = ref(true);
        const erreur     = ref('');
        const filtre     = ref('Tous');
        const niveaux    = ['Tous', 'L1', 'L2', 'L3'];

        // Valeur calculée : recalculée dès que formations ou filtre change
        const formationsFiltrees = computed(() =>
            filtre.value === 'Tous'
                ? formations.value
                : formations.value.filter(f => f.niveau === filtre.value)
        );

        onMounted(async () => {
            try {
                const reponse = await fetch(API);
                if (!reponse.ok) throw new Error('HTTP ' + reponse.status);
                formations.value = await reponse.json();
            } catch (e) {
                erreur.value = 'Erreur de chargement : ' + e.message;
            } finally {
                chargement.value = false;
            }
        });

        return { formationsFiltrees, chargement, erreur, filtre, niveaux };
    }
}).mount('#app');
