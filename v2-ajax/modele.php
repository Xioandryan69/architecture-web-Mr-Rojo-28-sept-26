<?php 

require_once __DIR__ . '/db.php';

function countFormation(): int
{
    $pdo = getDb();
    $stmt = $pdo->query("SELECT count(id) FROM formations");
    return (int) $stmt->fetchColumn();
}

function listerFormations(int $limit = 10, int $offset = 0): array
{
    $pdo = getDb();
    $stmt = $pdo->prepare('SELECT id, titre, description, niveau FROM formations ORDER BY niveau, titre LIMIT :limit OFFSET :offset');
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getFormationById(int $idFormation)
{
    $pdo = getDb();
    $stmt = $pdo->prepare("SELECT * FROM formations WHERE id = :id");
    $stmt->bindValue(':id', $idFormation, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}