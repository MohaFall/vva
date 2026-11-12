<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['typeProfil'] !== 'EN') {
    header('Location: login.php');
    exit;
}

include('../config.php');

try {
    $stmt = $pdo->query("SELECT CODEANIM, NOMANIM, DATECREATIONANIM FROM ANIMATION ORDER BY DATECREATIONANIM DESC");
    $animations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des animations : " . $e->getMessage());
}
?>

<?php include('../includes/header.php'); ?>
<h1>Liste des Animations</h1>

<table border="1">
    <thead>
        <tr>
            <th>Code</th>
            <th>Nom</th>
            <th>Date de création</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($animations as $animation): ?>
            <tr>
                <td><?= htmlspecialchars($animation['CODEANIM']); ?></td>
                <td><?= htmlspecialchars($animation['NOMANIM']); ?></td>
                <td><?= htmlspecialchars($animation['DATECREATIONANIM']); ?></td>
                <td>
                    <a href="edit_animation.php?codeanim=<?= urlencode($animation['CODEANIM']); ?>">Modifier</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include('../includes/footer.php'); ?>
