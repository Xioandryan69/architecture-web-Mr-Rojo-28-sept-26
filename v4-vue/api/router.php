<?php
// Routeur pour le serveur intégré de PHP :  php -S localhost:8000 router.php
// (sous Apache, le fichier .htaccess joue le même rôle)
$chemin = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

if (str_starts_with($chemin, '/api/')) {
    require __DIR__ . '/api.php';
    return true;
}
return false; // fichiers statiques (index.html, app.js, style.css) servis tels quels
