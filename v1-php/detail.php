<?php
require_once __DIR__ . '/modele.php';

$id = isset($_GET['id']) ? (int) $_GET['id'] : 1;
$formation = getFormationById($id);

function e(string $texte): string
{
    return htmlspecialchars($texte, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= $formation ? e($formation['titre']) : 'Formation non trouvée' ?> — V1 PHP</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Détail de la formation</h1>
        <p>V1 — page générée par le serveur (PHP + SQLite)</p>
    </header>

    <main class="container">
        <?php if ($formation): ?>
            <article class="card detail-card">
                <h2><?= e($formation['titre']) ?></h2>
                <p><?= e($formation['description']) ?></p>
                <span class="badge"><?= e($formation['niveau']) ?></span>
            </article>
        <?php else: ?>
            <p class="message">Aucune formation trouvée avec cet identifiant.</p>
        <?php endif; ?>

        <p><a href="index.php" class="btn-retour">&larr; Retour à la liste</a></p>
    </main>
</body>
</html>