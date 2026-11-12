<?php
// Démarrage de la session
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirige vers la page de connexion si non connecté
    exit;
}
?>

<?php include('../includes/header.php'); ?>
<h1>Tableau de bord</h1>
<p>Bienvenue sur votre espace personnel, <?= htmlspecialchars($_SESSION['username']); ?>.</p>

<?php if ($_SESSION['typeProfil'] === 'VA'): ?>
    <!-- Vacancier -->
    <p>En tant que vacancier, vous pouvez :</p>
    <ul>
        <li><a href="activities.php">Consulter les activités disponibles</a></li>
        <li><a href="my_activities.php">Voir les activités où vous êtes inscrit</a></li>
    </ul>
<?php elseif ($_SESSION['typeProfil'] === 'EN'): ?>
    <!-- Encadrant -->
    <p>En tant qu'encadrant, vous pouvez :</p>
    <ul>
        <li><a href="encadrant_activities.php">Consulter les activités que vous encadrez</a></li>
        <li><a href="create_animation.php">Créer une animation</a></li>
        <li><a href="edit_animation_list.php">Modifier une animation</a></li>
        <li><a href="create_activity.php">Créer une activité</a></li>
        <li><a href="list_activities.php">Gérer les activités</a></li>
        <h2>-- Statistiques --</h2>
        <li><a href="most_popular.php">Activité la plus populaire</a></li>
        <li><a href="vacancier_activities.php">Activité par vacancier</a></li>
        <li><a href="vacancier_vip.php">Les  3 vacanciers VIP (avec le plus d'inscription)</a></li>

    </ul>
<?php endif; ?>

<?php include('../includes/footer.php'); ?>
