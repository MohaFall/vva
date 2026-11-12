<?php
session_start();

// Only a logged-in supervisor (encadrant) can see statistics
if (!isset($_SESSION['user_id']) || $_SESSION['typeProfil'] !== 'EN') {
    header('Location: login.php');
    exit;
}

include('../config.php'); 
include('../includes/header.php');

try {
    // Préparer la requête pour récupérer l'activité la plus populaire
    $stmt = $pdo->prepare("
        SELECT anim.NOMANIM, ins.CODEANIM, COUNT(*) AS nbInscription
        FROM inscription ins
        INNER JOIN animation anim ON ins.CODEANIM = anim.CODEANIM
        WHERE ins.DATEANNULE IS NULL
        GROUP BY ins.CODEANIM, anim.NOMANIM
        ORDER BY nbInscription DESC
        LIMIT 1

    ");
    $stmt->execute();
    $activity = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération de l'activité : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Activité la plus populaire</title>
</head>
<body>
    <h1 style="text-align:center;">Activité la plus populaire</h1>

    <?php if ($activity): ?>
        <table>
            <tr>
                <th>Code de l'animation</th>
                <th>Nom</th>
                <th>Nombre d'inscriptions</th>
            </tr>
            <tr>
                <td><?= htmlspecialchars($activity['CODEANIM']) ?></td>
                <td><?= htmlspecialchars($activity['NOMANIM']) ?></td>
                <td><?= htmlspecialchars($activity['nbInscription']) ?></td>
            </tr>
        </table>
    <?php else: ?>
        <p style="text-align:center;">Aucune activité trouvée.</p>
    <?php endif; ?>
</body>
</html>
