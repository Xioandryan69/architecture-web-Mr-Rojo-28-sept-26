<?php
require __DIR__ . '/db.php';

header('Content-Type: application/json; charset=utf-8');

// CORS : Autoriser POST et OPTIONS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
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
$chemin  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$parties = array_values(array_filter(explode('/', $chemin)));

if (($parties[0] ?? '') !== 'api' || ($parties[1] ?? '') !== 'formations') {
    repondre(404, ['erreur' => 'Ressource inconnue']);
}

$db = getDb();
$id = $parties[2] ?? null;

// GESTION DU GET
if ($methode === 'GET') {
    if ($id === null) {
        $rows = $db->query('SELECT * FROM formations ORDER BY niveau, titre')->fetchAll();
        repondre(200, $rows);
    }
    $stmt = $db->prepare('SELECT * FROM formations WHERE id = ?');
    $stmt->execute([(int)$id]);
    $formation = $stmt->fetch();
    $formation ? repondre(200, $formation) : repondre(404, ['erreur' => "Formation $id introuvable"]);
}

// GESTION DU POST
if ($methode === 'POST') {
    $donnees = json_decode(file_get_contents('php://input'), true);
    
    $titre = trim($donnees['titre'] ?? '');
    $description = trim($donnees['description'] ?? '');
    $niveau = trim($donnees['niveau'] ?? '');

    if (empty($titre) || empty($description) || !in_array($niveau, ['L1', 'L2', 'L3'])) {
        repondre(400, ['erreur' => 'Champs invalides ou manquants']);
    }

    $stmt = $db->prepare('INSERT INTO formations (titre, description, niveau) VALUES (?, ?, ?)');
    $stmt->execute([$titre, $description, $niveau]);

    $nouvelleFormation = [
        'id' => (int)$db->lastInsertId(),
        'titre' => $titre,
        'description' => $description,
        'niveau' => $niveau
    ];

    repondre(201, $nouvelleFormation);
}

// Si autre méthode
header('Allow: GET, POST');
repondre(405, ['erreur' => 'Méthode non autorisée']);