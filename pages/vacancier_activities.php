<?php
session_start();

// Only a logged-in supervisor (encadrant) can see statistics
if (!isset($_SESSION['user_id']) || $_SESSION['typeProfil'] !== 'EN') {
    header('Location: login.php');
    exit;
}

include('../config.php'); 
include('../includes/header.php');

$vacanciers = [];
$activites = [];
$selectedVacancier = null;

// Récupérer tous les vacanciers
try {
    $stmt = $pdo->prepare("SELECT USER, NOMCOMPTE, PRENOMCOMPTE FROM compte WHERE TYPEPROFIL = :type");
    $stmt->execute([':type' => 'VA']);
    $vacanciers = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors du chargement des vacanciers : " . $e->getMessage());
}

// Si un vacancier est sélectionné
if (isset($_GET['vacancier'])) {
    $selectedVacancier = $_GET['vacancier'];

    // Récupérer les activités auxquelles le vacancier est inscrit
    try {
        $stmt = $pdo->prepare("
            SELECT CODEANIM, DATEINSCRIP
            FROM inscription
            WHERE USER = :id
        ");
        $stmt->execute([':id' => $selectedVacancier]);
        $activites = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Erreur lors de la récupération des activités : " . $e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Activités par vacancier</title>
<body>
    <h1 style="text-align:center;">Voir les activités d’un vacancier</h1>

    <form method="GET" action="">
        <label for="vacancier">Choisir un vacancier :</label>
        <select name="vacancier" id="vacancier" onchange="this.form.submit()">
            <option value="">-- Sélectionner --</option>
            <?php foreach ($vacanciers as $v): ?>
                <option value="<?= ($v['USER']) ?>" <?= ($v['USER'] === $selectedVacancier) ? 'selected' : '' ?>>
                <?= ($v['NOMCOMPTE'] . ' ' . $v['PRENOMCOMPTE']) ?>
                </option>

            <?php endforeach; ?>
        </select>
    </form>

    <?php if ($selectedVacancier && $activites): ?>
        <h2 style="text-align:center;">Activités du vacancier</h2>
        <table>
            <tr>
                <th>Code</th>
                <th>Date d'inscription</th>
            </tr>
            <?php foreach ($activites as $act): ?>
                <tr>
                    <td><?= htmlspecialchars($act['CODEANIM']) ?></td>
                    <td><?= htmlspecialchars($act['DATEINSCRIP']) ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php elseif ($selectedVacancier): ?>
        <p style="text-align:center;">Ce vacancier n'est inscrit à aucune activité.</p>
    <?php endif; ?>
</body>
</html>
