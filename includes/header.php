<?php
// Vérifier si une session est déjà active avant de la démarrer
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style.css">
    <title>GACTI - Gestion des activités</title>
</head>
<body>
<header>
    <h2>GACTI - Gestion des Activités</h2>
    <nav>
        <?php if (isset($_SESSION['user_id'])): ?>
            <p>Bienvenue, <?= htmlspecialchars($_SESSION['username']); ?> !</p>
            <a href="../pages/dashboard.php">Tableau de bord</a> |
            <a href="../pages/logout.php">Déconnexion</a>
        <?php endif; ?>
    </nav>
</header>
<main>
