<?php
// Démarrage de la session
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirection vers la page de connexion
    exit;
}

// Inclusion du fichier de configuration
include('../config.php');

// Récupération des activités de l'utilisateur connecté
$myActivities = [];
try {
    $sql = "SELECT act.CODEANIM, anim.NOMANIM, act.DATEACT, act.HRRDVACT 
            FROM INSCRIPTION ins
            INNER JOIN ACTIVITE act ON ins.CODEANIM = act.CODEANIM AND ins.DATEACT = act.DATEACT
            INNER JOIN ANIMATION anim ON act.CODEANIM = anim.CODEANIM
            WHERE ins.USER = :user
            ORDER BY act.DATEACT";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['user' => $_SESSION['user_id']]);
    $myActivities = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des activités : " . $e->getMessage());
}
?>

<?php include('../includes/header.php'); ?>
<h1>Mes activités</h1>

<?php if (!empty($myActivities)): ?>
    <table border="1">
        <thead>
            <tr>
                <th>Code</th>
                <th>Nom</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($myActivities as $activity): ?>
                <tr>
                    <td><?= htmlspecialchars($activity['CODEANIM']); ?></td>
                    <td><?= htmlspecialchars($activity['NOMANIM']); ?></td>
                    <td><?= htmlspecialchars($activity['DATEACT']); ?></td>
                    <td><?= htmlspecialchars($activity['HRRDVACT']); ?></td>
                    <td>
                        <a href="toggle_registration.php?action=unregister&code=<?= urlencode($activity['CODEANIM']); ?>&date=<?= urlencode($activity['DATEACT']); ?>&redirect=my_activities.php">Se désinscrire</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Vous n'êtes inscrit(e) à aucune activité.</p>
<?php endif; ?>

<?php include('../includes/footer.php'); ?>
