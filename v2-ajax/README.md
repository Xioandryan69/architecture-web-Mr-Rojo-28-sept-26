# V2 — Site PHP + AJAX (API simple)

Deuxième version de la série « Une page, quatre architectures ».
Même page, mêmes données, même CSS que la V1.

Changement : PHP sert toujours la page, mais **sans la liste**. Un script JavaScript
appelle ensuite `api.php`, qui renvoie des **données JSON**, et le navigateur
construit les cards lui-même.

Ce n'est pas encore une API REST : l'action est passée en paramètre d'URL
(`?action=list`).

## Contenu

| Fichier | Rôle |
|---|---|
| `index.php` | page avec un conteneur vide `#liste` |
| `app.js` | `fetch` vers l'API, puis construction des cards en JavaScript |
| `api.php` | API simple : `?action=list`, `?action=get&id=…`, `?action=cards_html` |
| `db.php`, `init.sql` | connexion et données (identiques à la V1) |
| `style.css` | identique à la V1 |
| `seed.php`, `seed.sql` | génération de milliers de lignes pour le test de charge |

## Lancer

Prérequis : PHP 8 avec l'extension `pdo_sqlite`.

```bash
cd v2-ajax
php -S localhost:8000
```

Ouvrir http://localhost:8000

## À observer

1. Afficher le **code source** de la page (Ctrl+U) : la liste est vide.
   Pourtant les cards s'affichent : c'est `app.js` qui les a ajoutées.
2. Outils développeur (F12), onglet Réseau : deux requêtes au lieu d'une,
   la page puis `api.php?action=list`.
3. Ouvrir directement dans le navigateur :
   - http://localhost:8000/api.php?action=list → des **données** (JSON)
   - http://localhost:8000/api.php?action=cards_html → de la **présentation** (fragment HTML)

   Même contenu, deux philosophies. Laquelle permettrait à une application mobile
   de réutiliser l'API ?
4. Dans `app.js`, les cards sont construites en concaténant des chaînes de caractères.
   Imaginez ce code avec un filtre, un formulaire et des mises à jour partielles.
5. Tester `api.php?action=get&id=999` : la réponse signale une erreur,
   mais quel est le code HTTP renvoyé ?

## Test de charge : comparaison avec la V1

```bash
php seed.php          # ajoute 10 000 formations
php seed.php reset    # revenir aux 6 formations d'origine
```

Pour comparer sur exactement les mêmes données, vous pouvez aussi copier la base
de la V1 : `cp ../v1-php/data.sqlite .`

Mesurer dans l'onglet Réseau (cache désactivé) la taille de la page et celle de
`api.php?action=list`, puis dans l'onglet Performance le délai avant l'affichage
des cards.

```bash
curl -o /dev/null -s -w "%{size_download} octets, %{time_total}s\n" http://localhost:8000/
curl -o /dev/null -s -w "%{size_download} octets, %{time_total}s\n" "http://localhost:8000/api.php?action=list"
```

Questions : quelle version transfère le moins de données ? Laquelle affiche
les premières cards le plus tôt ? Où se fait le travail de construction du HTML ?

## Exercices

1. Ajouter une pagination à l'API (`?action=list&page=2`) et un bouton
   « Charger plus » dans `app.js`. Mesurer à nouveau avec 10 000 lignes.
2. Créer une page détail qui appelle `api.php?action=get&id=…`.
3. Faire renvoyer un code HTTP 404 par `action=get` quand la formation n'existe pas.
