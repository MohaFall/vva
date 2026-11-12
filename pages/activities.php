<?php
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirection vers la page de connexion
    exit;
}

// Inclusion du fichier de configuration
include('../config.php');

// Récupération des animations disponibles pour le formulaire de tri
$animations = [];
try {
    $sql = "SELECT CODEANIM, NOMANIM FROM ANIMATION ORDER BY NOMANIM";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $animations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des animations : " . $e->getMessage());
}

// Récupération des activités avec leur statut d'inscription
$activities = [];
try {
    $sql = "SELECT act.CODEANIM, anim.NOMANIM, act.DATEACT, act.HRRDVACT, act.CODEETATACT, anim.NBREPLACEANIM, 
                   (SELECT COUNT(*) 
                    FROM INSCRIPTION ins 
                    WHERE ins.USER = :user AND ins.CODEANIM = act.CODEANIM AND ins.DATEACT = act.DATEACT) AS IS_INSCRIT
            FROM ACTIVITE act
            INNER JOIN ANIMATION anim ON act.CODEANIM = anim.CODEANIM";

    // Filtrer par animation si un code est passé en GET
    if (isset($_GET['animation']) && !empty($_GET['animation'])) {
        $sql .= " WHERE act.CODEANIM = :codeanim";
    }
    $sql .= " ORDER BY anim.NOMANIM, act.DATEACT";

    $stmt = $pdo->prepare($sql);

    // Ajouter le paramètre de filtrage si nécessaire
    if (isset($_GET['animation']) && !empty($_GET['animation'])) {
        $stmt->execute(['user' => $_SESSION['user_id'], 'codeanim' => $_GET['animation']]);
    } else {
        $stmt->execute(['user' => $_SESSION['user_id']]);
    }

    $activities = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erreur lors de la récupération des activités : " . $e->getMessage());
}
?>

<?php include('../includes/header.php'); ?>
<h1>Liste des activités disponibles</h1>

<!-- Formulaire pour filtrer les activités par animation -->
<form method="GET" action="">
    <label for="animation">Choisir une animation :</label>
    <select name="animation" id="animation" onchange="this.form.submit()">
        <option value="">Toutes les animations</option>
        <?php foreach ($animations as $animation): ?>
            <option value="<?= htmlspecialchars($animation['CODEANIM']); ?>"
                <?= (isset($_GET['animation']) && $_GET['animation'] === $animation['CODEANIM']) ? 'selected' : ''; ?>>
                <?= htmlspecialchars($animation['NOMANIM']); ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>

<!-- Tableau des activités -->
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
                <?php
                // Ajouter ce filtre pour les vacanciers
                if (
                    ($activity['CODEETATACT'] == 'AN' || $activity['CODEETATACT'] == 'CO')
                ) {
                    continue; // On saute cette activité
                }
                ?>
                <tr>
                    <td><?= htmlspecialchars($activity['CODEANIM']); ?></td>
                    <td><?= htmlspecialchars($activity['NOMANIM']); ?></td>
                    <td><?= htmlspecialchars($activity['DATEACT']); ?></td>
                    <td><?= htmlspecialchars($activity['HRRDVACT']); ?></td>
                    <td><?= htmlspecialchars($activity['NBREPLACEANIM']); ?></td>
                    <td>
                        <?php if ($activity['IS_INSCRIT'] > 0): ?>
                            <!-- L'utilisateur est inscrit -->
                            <a href="toggle_registration.php?action=unregister&code=<?= urlencode($activity['CODEANIM']); ?>&date=<?= urlencode($activity['DATEACT']); ?>&redirect=activities.php">Se désinscrire</a>
                        <?php endif; ?>

                        <?php if ($activity['IS_INSCRIT'] == 0 && $activity['NBREPLACEANIM'] > 0): ?>
                            <!-- L'utilisateur n'est pas inscrit -->
                            <a href="toggle_registration.php?action=register&code=<?= urlencode($activity['CODEANIM']); ?>&date=<?= urlencode($activity['DATEACT']); ?>&redirect=activities.php">S'inscrire</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>Aucune activité disponible pour le moment.</p>
<?php endif; ?>


<?php include('../includes/footer.php'); ?>
