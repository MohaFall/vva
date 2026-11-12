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

// Vérification des paramètres GET
if (!isset($_GET['code'], $_GET['date']) || empty($_GET['code']) || empty($_GET['date'])) {
    die("Erreur : paramètres invalides.");
}

$codeAnim = $_GET['code'];
$dateAct = $_GET['date'];

// Récupération des inscrits pour l'activité spécifiée
$inscrits = [];
try {
    $sql = "SELECT c.NOMCOMPTE, c.PRENOMCOMPTE, c.ADRMAILCOMPTE, c.NOTELCOMPTE
            FROM INSCRIPTION ins
            INNER JOIN COMPTE c ON ins.USER = c.USER
            WHERE ins.CODEANIM = :codeanim AND ins.DATEACT = :dateact";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'codeanim' => $codeAnim,
        'dateact' => $dateAct,
    ]);
    $inscrits = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des inscrits : " . $e->getMessage());
}
?>

<?php include('../includes/header.php'); ?>
<h1>Liste des inscrits</h1>

<?php if (!empty($inscrits)): ?>
    <table border="1">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Téléphone</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($inscrits as $inscrit): ?>
                <tr>
                    <td><?= htmlspecialchars($inscrit['NOMCOMPTE']); ?></td>
                    <td><?= htmlspecialchars($inscrit['PRENOMCOMPTE']); ?></td>
                    <td><?= htmlspecialchars($inscrit['ADRMAILCOMPTE']); ?></td>
                    <td><?= htmlspecialchars($inscrit['NOTELCOMPTE']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aucun inscrit pour cette activité.</p>
<?php endif; ?>

<a href="encadrant_activities.php">Retour à mes activités encadrées</a>
<?php include('../includes/footer.php'); ?>
