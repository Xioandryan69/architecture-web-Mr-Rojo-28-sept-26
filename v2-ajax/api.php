<?php
// V2 — API simple
require_once __DIR__ . '/modele.php';

$action = $_GET['action'] ?? '';

switch ($action) {

    // api.php?action=list&limit=10&offset=0
    case 'list':
        header('Content-Type: application/json; charset=utf-8');
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
        
        $formations = listerFormations($limit, $offset);
        echo json_encode($formations, JSON_UNESCAPED_UNICODE);
        break;

    // api.php?action=get&id=3 -> Récupérer une formation spécifique
    case 'get':
        header('Content-Type: application/json; charset=utf-8');
        $id = (int)($_GET['id'] ?? 0);
        $formation = getFormationById($id);

        if (!$formation) {
            http_response_code(404);
            echo json_encode(['erreur' => 'Formation introuvable'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        echo json_encode($formation, JSON_UNESCAPED_UNICODE);
        break;

    // Variante HTML fragment
    case 'cards_html':
        header('Content-Type: text/html; charset=utf-8');
        $formations = listerFormations(10, 0);
        foreach ($formations as $f) {
            $t = htmlspecialchars($f['titre']);
            $d = htmlspecialchars($f['description']);
            $n = htmlspecialchars($f['niveau']);
            echo "<article class=\"card\"><h2>$t</h2><p>$d</p><span class=\"badge\">$n</span></article>\n";
        }
        break;

    default:
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(400);
        echo json_encode(['erreur' => 'Action inconnue'], JSON_UNESCAPED_UNICODE);
}