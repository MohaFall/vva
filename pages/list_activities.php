<?php
// Démarrage de la session
session_start();

// Vérification si l'utilisateur est connecté et encadrant
if (!isset($_SESSION['user_id']) || $_SESSION['typeProfil'] !== 'EN') {
    header('Location: login.php'); // Redirection si non connecté ou si non encadrant
    exit;
}

// Inclusion du fichier de configuration
include('../config.php');

// Récupération des activités existantes
$activities = [];
try {
    $sql = "SELECT a.CODEANIM, a.DATEACT, a.CODEETATACT, a.HRRDVACT, a.PRIXACT, a.HRDEBUTACT, a.HRFINACT, 
                   a.DATEANNULEACT, a.NOMRESP, a.PRENOMRESP, an.NOMANIM, e.NOMETATACT
            FROM ACTIVITE a
            INNER JOIN ANIMATION an ON a.CODEANIM = an.CODEANIM
            INNER JOIN ETAT_ACT e ON a.CODEETATACT = e.CODEETATACT
            ORDER BY a.DATEACT DESC, a.HRRDVACT";
    $stmt = $pdo->query($sql);
    $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des activités : " . $e->getMessage());
}
?>

<?php include('../includes/header.php'); ?>
<h1>Liste des Activités</h1>

<table border="1">
    <thead>
        <tr>
            <th>Animation</th>
            <th>Date</th>
            <th>Heure RDV</th>
            <th>Prix (€)</th>
            <th>Heure Début</th>
            <th>Heure Fin</th>
            <th>Responsable</th>
            <th>État</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (count($activities) > 0): ?>
            <?php foreach ($activities as $activity): ?>
                <tr>
                    <td><?= htmlspecialchars($activity['NOMANIM']); ?></td>
                    <td><?= htmlspecialchars($activity['DATEACT']); ?></td>
                    <td><?= htmlspecialchars($activity['HRRDVACT']); ?></td>
                    <td><?= htmlspecialchars($activity['PRIXACT']); ?></td>
                    <td><?= htmlspecialchars($activity['HRDEBUTACT']); ?></td>
                    <td><?= htmlspecialchars($activity['HRFINACT']); ?></td>
                    <td><?= htmlspecialchars($activity['NOMRESP'] . ' ' . $activity['PRENOMRESP']); ?></td>
                    <td><?= htmlspecialchars($activity['NOMETATACT']); ?></td>
                    <td>
                        <!-- Liens pour modifier ou fermer l'activité -->
                        <a href="edit_activity.php?codeanim=<?= urlencode($activity['CODEANIM']); ?>&dateact=<?= urlencode($activity['DATEACT']); ?>">Modifier</a>
                        <?php if ($activity['CODEETATACT'] !== 'FE'): ?>
                            | <a href="close_activity.php?codeanim=<?= urlencode($activity['CODEANIM']); ?>&dateact=<?= urlencode($activity['DATEACT']); ?>" onclick="return confirm('Êtes-vous sûr de vouloir fermer cette activité ?');">Fermer</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr>
                <td colspan="9">Aucune activité trouvée.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php include('../includes/footer.php'); ?>
