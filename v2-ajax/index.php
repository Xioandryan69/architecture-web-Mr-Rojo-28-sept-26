<?php
// V2 — PHP sert encore la page, mais la liste arrive ensuite par AJAX.
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formations — V2 AJAX</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>Nos formations</h1>
        <p>V2 — page PHP + liste chargée en AJAX (api.php)</p>
    </header>

    <!-- Conteneur vide : il sera rempli par app.js -->
    <main id="liste" class="liste">
        <p class="message">Chargement…</p>
    </main>

    <script src="app.js"></script>
</body>
</html>
