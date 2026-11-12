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

// Vérification des paramètres GET (codeanim et dateact)
if (!isset($_GET['codeanim']) || !isset($_GET['dateact'])) {
    die("Erreur : paramètres invalides.");
}

$codeAnim = $_GET['codeanim'];
$dateAct = $_GET['dateact'];

// Récupération des données de l'activité
try {
    $stmt = $pdo->prepare("SELECT * FROM ACTIVITE WHERE CODEANIM = :codeanim AND DATEACT = :dateact");
    $stmt->execute(['codeanim' => $codeAnim, 'dateact' => $dateAct]);
    $activity = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$activity) {
        die("Erreur : activité introuvable.");
    }
} catch (PDOException $e) {
    die("Erreur lors de la récupération de l'activité : " . $e->getMessage());
}

// Récupération des états d'activités existants
$etatsActivite = [];
try {
    $stmt = $pdo->query("SELECT CODEETATACT, NOMETATACT FROM ETAT_ACT");
    $etatsActivite = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des états d'activités : " . $e->getMessage();
}

// Gestion de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codeEtatAct = $_POST['codeetatact'];
    $hrrdvAct = $_POST['hrrdvact'];
    $prixAct = floatval($_POST['prixact']);
    $hrDebutAct = $_POST['hrdebutact'];
    $hrFinAct = $_POST['hrfinact'];

    try {
        // Mise à jour de l'activité
        $sql = "UPDATE ACTIVITE 
                SET CODEETATACT = :codeetatact, HRRDVACT = :hrrdvact, PRIXACT = :prixact, 
                    HRDEBUTACT = :hrdebutact, HRFINACT = :hrfinact, DATEANNULEACT = NULL
                WHERE CODEANIM = :codeanim AND DATEACT = :dateact";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'codeetatact' => $codeEtatAct,
            'hrrdvact' => $hrrdvAct,
            'prixact' => $prixAct,
            'hrdebutact' => $hrDebutAct,
            'hrfinact' => $hrFinAct,
            'codeanim' => $codeAnim,
            'dateact' => $dateAct,
        ]);
        $message = "Activité modifiée avec succès.";
    } catch (PDOException $e) {
        $error = "Erreur lors de la modification : " . $e->getMessage();
    }
}
?>

<?php include('../includes/header.php'); ?>
<h1>Modifier une Activité</h1>

<!-- Affichage des messages -->
<?php if (isset($message)): ?>
    <p style="color: green;"><?= htmlspecialchars($message); ?></p>
<?php elseif (isset($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error); ?></p>
<?php endif; ?>

<!-- Formulaire de modification d'activité -->
<form method="POST" action="">
    <label for="codeetatact">État de l'activité :</label>
    <select name="codeetatact" id="codeetatact" required>
        <?php foreach ($etatsActivite as $etat): ?>
            <option value="<?= htmlspecialchars($etat['CODEETATACT']); ?>" <?= $etat['CODEETATACT'] === $activity['CODEETATACT'] ? 'selected' : ''; ?>>
                <?= htmlspecialchars($etat['NOMETATACT']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="hrrdvact">Heure de rendez-vous :</label>
    <input type="time" name="hrrdvact" id="hrrdvact" value="<?= htmlspecialchars($activity['HRRDVACT']); ?>" required>

    <label for="prixact">Prix (€) :</label>
    <input type="number" name="prixact" id="prixact" step="0.01" value="<?= htmlspecialchars($activity['PRIXACT']); ?>" required>

    <label for="hrdebutact">Heure de début :</label>
    <input type="time" name="hrdebutact" id="hrdebutact" value="<?= htmlspecialchars($activity['HRDEBUTACT']); ?>" required>

    <label for="hrfinact">Heure de fin :</label>
    <input type="time" name="hrfinact" id="hrfinact" value="<?= htmlspecialchars($activity['HRFINACT']); ?>" required>


    <button type="submit">Modifier l'activité</button>
</form>

<?php include('../includes/footer.php'); ?>
