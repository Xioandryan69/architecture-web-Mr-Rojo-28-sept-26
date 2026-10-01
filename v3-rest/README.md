# V3 — API REST

Troisième version de la série « Une page, quatre architectures ».
Même page, mêmes données, même CSS.

Changement : l'API de la V2 est réorganisée selon les principes **REST**.

| V2 (API simple) | V3 (REST) |
|---|---|
| `api.php?action=list` | `GET /api/formations` |
| `api.php?action=get&id=3` | `GET /api/formations/3` |
| toujours 200, même en cas d'erreur | 200, 404, 405 selon le résultat |

- L'**URL** désigne une ressource (un nom), pas une action.
- Le **verbe HTTP** porte l'action : GET lire, POST créer, PUT modifier, DELETE supprimer.
- Le **code HTTP** porte le résultat.

## Contenu

| Fichier | Rôle |
|---|---|
| `index.html` | page statique (plus besoin de PHP pour la page) |
| `app.js` | identique à la V2, seule l'URL de l'API change |
| `api.php` | API REST : lecture de la méthode et du chemin, réponses JSON + codes HTTP |
| `router.php` | routeur pour le serveur intégré de PHP : envoie `/api/...` vers `api.php` |
| `.htaccess` | même rôle que `router.php` sous Apache |
| `db.php`, `init.sql`, `style.css` | identiques aux versions précédentes |

## Lancer

Prérequis : PHP 8 avec l'extension `pdo_sqlite`.

```bash
cd v3-rest
php -S localhost:8000 router.php
```

Ouvrir http://localhost:8000

## À observer

1. Tester dans le navigateur :
   - http://localhost:8000/api/formations
   - http://localhost:8000/api/formations/3
   - http://localhost:8000/api/formations/99 → 404
2. Tester un autre verbe :
   ```bash
   curl -i -X DELETE http://localhost:8000/api/formations/3   # → 405
   ```
3. Comparer `app.js` avec celui de la V2 : seule l'URL a changé
   (plus une vérification de `reponse.ok`). Le client dépend d'un contrat,
   pas de l'implémentation du serveur.
4. Onglet Réseau des outils développeur : observer la colonne « Statut ».

## Exercices

1. Ajouter `POST /api/formations` : lire le JSON reçu (`file_get_contents('php://input')`),
   insérer la formation, répondre **201 Created** avec la formation créée.
2. Ajouter `DELETE /api/formations/{id}` : répondre **204 No Content**, ou 404.
3. Ajouter `PUT /api/formations/{id}` pour modifier une formation.
4. Renvoyer **400 Bad Request** quand le titre est vide lors d'un POST.
