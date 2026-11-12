<?php
session_start();

// Only a logged-in supervisor (encadrant) can see statistics
if (!isset($_SESSION['user_id']) || $_SESSION['typeProfil'] !== 'EN') {
    header('Location: login.php');
    exit;
}

include('../config.php'); 
include('../includes/header.php');

// Exécuter la requête
$vacanciers = [];

try {
    $stmt = $pdo->query("
        SELECT 
            c.USER,
            c.NOMCOMPTE,
            c.PRENOMCOMPTE,
            COUNT(i.NOINSCRIP) AS total_inscriptions
        FROM inscription i
        JOIN compte c ON i.USER = c.USER
        WHERE c.TYPEPROFIL = 'VA'
        GROUP BY c.USER, c.NOMCOMPTE, c.PRENOMCOMPTE
        ORDER BY total_inscriptions DESC
        LIMIT 3
    ");
    $vacanciers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur SQL : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Top 3 Vacanciers VIP</title>
</head>
<body>
    <h1 style="text-align:center;">Top 3 des vacanciers avec le plus d'inscriptions</h1>

    <table border="1" style="margin:auto; border-collapse:collapse;">
        <tr>
            <th>Identifiant</th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Nombre d'inscriptions</th>
        </tr>
        <?php foreach ($vacanciers as $v): ?>
            <tr>
                <td><?= htmlspecialchars($v['USER']) ?></td>
                <td><?= htmlspecialchars($v['NOMCOMPTE']) ?></td>
                <td><?= htmlspecialchars($v['PRENOMCOMPTE']) ?></td>
                <td><?= $v['total_inscriptions'] ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
