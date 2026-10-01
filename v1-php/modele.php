<?php 
require __DIR__.'/db.php';



function listerFormations(int $limit =10,int $offset=0)
{
    $pdo=getDb();

    // methode simple 
    //  $formations = $formation->query('SELECT id, titre, description, niveau FROM formations ORDER BY niveau, titre')->fetchAll();
    // methode optimiser et securiser 
    $stmt=$pdo->prepare('SELECT id, titre, description, niveau FROM formations ORDER BY niveau, titre  LIMIT :limit OFFSET :offset ');
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    $formations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $formations;
}


function compterFormations(): int
{
    $pdo = getDb();
    return (int) $pdo->query('SELECT COUNT(*) FROM formations')->fetchColumn();
}



function getFormationById($idFormation)
{
    $pdo=getDb();

    $stmt = $pdo->prepare("SELECT * FROM formations WHERE id = :id");
    $stmt->bindValue(':id',(int)$idFormation , PDO::PARAM_INT);
    $stmt->execute();
    $formation = $stmt->fetch(PDO::FETCH_ASSOC);
    return $formation;
}

?>