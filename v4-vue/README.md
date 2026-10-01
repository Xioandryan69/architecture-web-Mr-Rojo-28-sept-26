# V4 — Front Vue.js + API REST

Quatrième version de la série « Une page, quatre architectures ».
Même page, mêmes données, même CSS (plus des boutons de filtre).

Changement : le front devient **une application à part entière**, écrite avec Vue.js,
séparée de l'API. On ne manipule plus le DOM à la main : on décrit l'interface
à partir des données (l'état), et Vue la met à jour.

## Contenu

```
v4-vue/
├── api/      l'API REST de la V3, avec en plus les en-têtes CORS
│   ├── api.php
│   ├── router.php, .htaccess
│   └── db.php, init.sql
└── front/    l'application Vue.js
    ├── index.html   le template (v-for, @click, composant formation-card)
    ├── app.js       l'état (ref, computed), le chargement (onMounted + fetch)
    └── style.css
```

## Lancer : deux serveurs

Prérequis : PHP 8 avec `pdo_sqlite`, et une connexion internet
(Vue est chargé depuis un CDN).

```bash
# Terminal 1 — l'API
cd v4-vue/api
php -S localhost:8000 router.php

# Terminal 2 — le front
cd v4-vue/front
php -S localhost:5173
```

Ouvrir http://localhost:5173

## À observer

1. Deux ports = deux **origines** différentes. Commenter la ligne
   `Access-Control-Allow-Origin` dans `api/api.php` et recharger :
   lire l'erreur CORS dans la console du navigateur.
2. Cliquer sur les filtres L1 / L2 / L3 : aucune ligne de code ne touche au DOM.
   On change seulement la variable `filtre`, et `formationsFiltrees`
   (une valeur `computed`) est recalculée automatiquement.
3. Dans `index.html`, les `{{ }}` échappent le contenu : plus besoin de fonction `e()`.
4. Le composant `FormationCard` est réutilisable : c'est l'unité de base
   d'une application Vue.
5. Afficher le code source de la page : la liste n'y figure pas.
   Qu'en conclure pour le référencement et pour les mobiles lents ?

## Exercices

1. Ajouter un champ de recherche par titre (`v-model` + un second critère
   dans `computed`).
2. Si vous avez fait l'exercice POST de la V3 : ajouter un formulaire d'ajout
   qui appelle `POST /api/formations` et met à jour la liste sans recharger.
3. Migrer le front vers un vrai projet Vue : `npm create vue@latest`,
   puis transformer `FormationCard` en fichier `FormationCard.vue`.
4. Pour aller plus loin : découvrir le rendu côté serveur (SSR) avec Nuxt.
