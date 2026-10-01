<?php
require_once __DIR__ . '/modele.php';

// Parameters handling
$limit = isset($_GET['limit']) ? max(1, (int)$_GET['limit']) : 10;

if (isset($_GET['offset'])) {
    $offset = max(0, (int)$_GET['offset']);
    $page = (int) floor($offset / $limit) + 1;
} else {
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    }
    
$totalFormations = compterFormations();
$totalPages = max(1, (int) ceil($totalFormations / $limit));
$page = min($page, $totalPages); // Évite de dépasser la dernière page
$offset = ($page - 1) * $limit;
$formations = listerFormations($limit, $offset);
function e(string $texte): string
{
    return htmlspecialchars($texte, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formations — V1 PHP</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Nos formations</h1>
        <p>V1 — page générée par le serveur (PHP + SQLite)</p>
    </header>

    <main>
        <section class="liste">
            <?php foreach ($formations as $f): ?>
                <article class="card">
                    <h2><?= e($f['titre']) ?></h2>
                    <p><?= e($f['description']) ?></p>
                    <span class="badge"><?= e($f['niveau']) ?></span>
                    <a href="detail.php?id=<?= urlencode((string)$f['id']) ?>" class="btn">Voir le détail</a>
                </article>
            <?php endforeach; ?>
        </section>

        <!-- Pagination -->
        <nav class="pagination">
            <?php if ($page > 1): ?>
                <a href="index.php?page=<?= $page - 1 ?>&limit=<?= $limit ?>" class="btn">&laquo; Précédent</a>
            <?php endif; ?>

            <span>Page <?= $page ?> sur <?= $totalPages ?></span>

            <?php if ($page < $totalPages): ?>
                <a href="index.php?page=<?= $page + 1 ?>&limit=<?= $limit ?>" class="btn">Suivant &raquo;</a>
            <?php endif; ?>
        </nav>
    </main>
</body>
</html>