<?php
// V4 — Même API REST que la V3, avec en plus les en-têtes CORS :
// le front (localhost:5173) et l'API (localhost:8000) sont deux origines différentes.
//
//   GET /api/formations        → 200 + liste
//   GET /api/formations/{id}   → 200 + une formation, ou 404
//   autre verbe                → 405
require __DIR__ . '/db.php';

header('Content-Type: application/json; charset=utf-8');

// CORS : autoriser le front à appeler l'API
header('Access-Control-Allow-Origin: *');   // en production : l'URL exacte du front
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {  // requête « preflight »
    http_response_code(204);
    exit;
}

function repondre(int $code, $donnees): void
{
    http_response_code($code);
    echo json_encode($donnees, JSON_UNESCAPED_UNICODE);
    exit;
}

$methode = $_SERVER['REQUEST_METHOD'];
$chemin  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);   // ex. /api/formations/3
$parties = array_values(array_filter(explode('/', $chemin)));  // ['api','formations','3']

if (($parties[0] ?? '') !== 'api' || ($parties[1] ?? '') !== 'formations') {
    repondre(404, ['erreur' => 'Ressource inconnue']);
}

if ($methode !== 'GET') {
    header('Allow: GET');
    repondre(405, ['erreur' => 'Méthode non autorisée']);
}

$db = getDb();
$id = $parties[2] ?? null;

if ($id === null) {
    // Collection
    $rows = $db->query('SELECT * FROM formations ORDER BY niveau, titre')->fetchAll();
    repondre(200, $rows);
}

// Élément
$stmt = $db->prepare('SELECT * FROM formations WHERE id = ?');
$stmt->execute([(int)$id]);
$formation = $stmt->fetch();

$formation
    ? repondre(200, $formation)
    : repondre(404, ['erreur' => "Formation $id introuvable"]);
