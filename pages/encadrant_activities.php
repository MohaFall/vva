<?php
// Démarrage de la session
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || $_SESSION['typeProfil'] !== 'EN') {
    header('Location: login.php'); // Redirection si non connecté ou si non encadrant
    exit;
}

// Inclusion du fichier de configuration
include('../config.php');

// Récupération des activités encadrées par l'utilisateur
$activities = [];
try {
    $sql = "SELECT act.CODEANIM, anim.NOMANIM, act.DATEACT, act.HRRDVACT, anim.NBREPLACEANIM
            FROM ACTIVITE act
            INNER JOIN ANIMATION anim ON act.CODEANIM = anim.CODEANIM
            WHERE act.NOMRESP = :nom AND act.PRENOMRESP = :prenom
            ORDER BY act.DATEACT";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'nom' => $_SESSION['username'],
        'prenom' => $_SESSION['prenom'], // Assure-toi que ce champ est bien stocké en session
    ]);
    $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des activités : " . $e->getMessage());
}
?>

<?php include('../includes/header.php'); ?>
<h1>Mes activités encadrées</h1>

<?php if (!empty($activities)): ?>
    <table border="1">
        <thead>
            <tr>
                <th>Code</th>
                <th>Nom</th>
                <th>Date</th>
                <th>Heure</th>
                <th>Places disponibles</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($activities as $activity): ?>
                <tr>
                    <td><?= htmlspecialchars($activity['CODEANIM']); ?></td>
                    <td><?= htmlspecialchars($activity['NOMANIM']); ?></td>
                    <td><?= htmlspecialchars($activity['DATEACT']); ?></td>
                    <td><?= htmlspecialchars($activity['HRRDVACT']); ?></td>
                    <td><?= htmlspecialchars($activity['NBREPLACEANIM']); ?></td>
                    <td>
                        <a href="view_inscriptions.php?code=<?= urlencode($activity['CODEANIM']); ?>&date=<?= urlencode($activity['DATEACT']); ?>">Voir les inscrits</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Vous n'encadrez aucune activité pour le moment.</p>
<?php endif; ?>

<?php include('../includes/footer.php'); ?>
