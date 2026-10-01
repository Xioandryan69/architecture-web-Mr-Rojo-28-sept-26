<?php
// V2 — Une API simple, PAS encore REST :
// l'action est passée en paramètre d'URL (?action=...).
require __DIR__ . '/db.php';

$db     = getDb();
$action = $_GET['action'] ?? '';

switch ($action) {

    // api.php?action=list  → toutes les formations en JSON
    case 'list':
        header('Content-Type: application/json; charset=utf-8');
        $rows = $db->query('SELECT * FROM formations ORDER BY niveau, titre')->fetchAll();
        echo json_encode($rows, JSON_UNESCAPED_UNICODE);
        break;

    // api.php?action=get&id=3  → une formation
    case 'get':
        header('Content-Type: application/json; charset=utf-8');
        $stmt = $db->prepare('SELECT * FROM formations WHERE id = ?');
        $stmt->execute([(int)($_GET['id'] ?? 0)]);
        echo json_encode($stmt->fetch() ?: ['erreur' => 'introuvable'], JSON_UNESCAPED_UNICODE);
        break;

    // Variante pédagogique : le serveur renvoie un FRAGMENT HTML
    // api.php?action=cards_html  → à comparer avec 'list' (JSON)
    case 'cards_html':
        header('Content-Type: text/html; charset=utf-8');
        foreach ($db->query('SELECT * FROM formations ORDER BY niveau, titre') as $f) {
            $t = htmlspecialchars($f['titre']);
            $d = htmlspecialchars($f['description']);
            $n = htmlspecialchars($f['niveau']);
            echo "<article class=\"card\"><h2>$t</h2><p>$d</p><span class=\"badge\">$n</span></article>\n";
        }
        break;

    default:
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['erreur' => 'action inconnue']);
}
