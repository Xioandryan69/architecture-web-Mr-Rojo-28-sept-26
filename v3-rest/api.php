<?php
// V3 — API REST complète
require_once __DIR__ . '/modele.php';

header('Content-Type: application/json; charset=utf-8');

function repondre(int $code, $donnees = null): void
{
    http_response_code($code);
    if ($donnees !== null) {
        echo json_encode($donnees, JSON_UNESCAPED_UNICODE);
    }
    exit;
}

$methode = $_SERVER['REQUEST_METHOD'];
$chemin  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$parties = array_values(array_filter(explode('/', $chemin)));

if (($parties[0] ?? '') !== 'api' || ($parties[1] ?? '') !== 'formations') {
    repondre(404, ['erreur' => 'Ressource inconnue']);
}

$id = isset($parties[2]) ? (int)$parties[2] : null;

switch ($methode) {
    case 'GET':
        if ($id === null) {
            $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 100;
            $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
            repondre(200, listerFormations($limit, $offset));
        } else {
            $formation = getFormationById($id);
            $formation 
                ? repondre(200, $formation) 
                : repondre(404, ['erreur' => "Formation $id introuvable"]);
        }
        break;

    case 'POST':
        if ($id !== null) {
            header('Allow: GET, PUT, DELETE');
            repondre(405, ['erreur' => 'Impossible d\'exécuter POST sur un élément spécifique']);
        }

        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $titre = trim($input['titre'] ?? '');
        $description = trim($input['description'] ?? '');
        $niveau = trim($input['niveau'] ?? 'L1');

        // Exercice 4 : Renvoyer 400 Bad Request si le titre est vide
        if (empty($titre)) {
            repondre(400, ['erreur' => 'Le champ titre est obligatoire']);
        }

        // Exercice 1 : POST /api/formations -> 201 Created
        $nouvelleFormation = ajouterFormation($titre, $description, $niveau);
        repondre(201, $nouvelleFormation);
        break;

    case 'PUT':
        if ($id === null) {
            header('Allow: GET, POST');
            repondre(405, ['erreur' => 'Un identifiant est requis pour modifier une formation']);
        }

        $input = json_decode(file_get_contents('php://input'), true) ?? [];
        $titre = trim($input['titre'] ?? '');
        $description = trim($input['description'] ?? '');
        $niveau = trim($input['niveau'] ?? 'L1');

        // Validation du titre
        if (empty($titre)) {
            repondre(400, ['erreur' => 'Le champ titre est obligatoire']);
        }

        // Exercice 3 : PUT /api/formations/{id}
        $formationModifiee = modifierFormation($id, $titre, $description, $niveau);
        if (!$formationModifiee) {
            repondre(404, ['erreur' => "Formation $id introuvable"]);
        }

        repondre(200, $formationModifiee);
        break;

    case 'DELETE':
        if ($id === null) {
            header('Allow: GET, POST');
            repondre(405, ['erreur' => 'Un identifiant est requis pour la suppression']);
        }

        // Exercice 2 : DELETE /api/formations/{id} -> 204 No Content ou 404
        $supprime = supprimerFormation($id);
        if (!$supprime) {
            repondre(404, ['erreur' => "Formation $id introuvable"]);
        }

        repondre(204);
        break;

    default:
        header('Allow: GET, POST, PUT, DELETE');
        repondre(405, ['erreur' => 'Méthode non autorisée']);
}