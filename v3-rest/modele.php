<?php
// V3 — Modèle d'accès à la base de données SQLite
require_once __DIR__ . '/db.php';

function countFormations(): int
{
    $pdo = getDb();
    $stmt = $pdo->query("SELECT COUNT(id) FROM formations");
    return (int) $stmt->fetchColumn();
}

function listerFormations(int $limit = 100, int $offset = 0): array
{
    $pdo = getDb();
    $stmt = $pdo->prepare('SELECT id, titre, description, niveau FROM formations ORDER BY niveau, titre LIMIT :limit OFFSET :offset');
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getFormationById(int $id): ?array
{
    $pdo = getDb();
    $stmt = $pdo->prepare('SELECT * FROM formations WHERE id = :id');
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    return $res ?: null;
}

function ajouterFormation(string $titre, string $description, string $niveau): array
{
    $pdo = getDb();
    $stmt = $pdo->prepare('INSERT INTO formations (titre, description, niveau) VALUES (:titre, :description, :niveau)');
    $stmt->execute([
        ':titre' => $titre,
        ':description' => $description,
        ':niveau' => $niveau
    ]);
    
    $id = (int)$pdo->lastInsertId();
    return [
        'id' => $id,
        'titre' => $titre,
        'description' => $description,
        'niveau' => $niveau
    ];
}

function modifierFormation(int $id, string $titre, string $description, string $niveau): ?array
{
    $pdo = getDb();
    
    if (!getFormationById($id)) {
        return null;
    }

    $stmt = $pdo->prepare('UPDATE formations SET titre = :titre, description = :description, niveau = :niveau WHERE id = :id');
    $stmt->execute([
        ':id' => $id,
        ':titre' => $titre,
        ':description' => $description,
        ':niveau' => $niveau
    ]);

    return [
        'id' => $id,
        'titre' => $titre,
        'description' => $description,
        'niveau' => $niveau
    ];
}

function supprimerFormation(int $id): bool
{
    $pdo = getDb();
    $stmt = $pdo->prepare('DELETE FROM formations WHERE id = :id');
    $stmt->execute([':id' => $id]);
    return $stmt->rowCount() > 0;
}