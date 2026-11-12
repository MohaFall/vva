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

// Gestion de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codeAnim = $_POST['codeanim'];
    $dateAct = $_POST['dateact'];
    $codeEtatAct = $_POST['codeetatact'];
    $hrrdvAct = $_POST['hrrdvact'];
    $prixAct = floatval($_POST['prixact']);
    $hrDebutAct = $_POST['hrdebutact'];
    $hrFinAct = $_POST['hrfinact'];
    $responsable = $_POST['responsable'];
    [$nomResp, $prenomResp] = explode('|', $responsable); // Découpe le nom et le prénom du responsable

    try {
        // Insertion de la nouvelle activité
        $sql = "INSERT INTO ACTIVITE (CODEANIM, DATEACT, CODEETATACT, HRRDVACT, PRIXACT, HRDEBUTACT, HRFINACT, NOMRESP, PRENOMRESP) 
                VALUES (:codeanim, :dateact, :codeetatact, :hrrdvact, :prixact, :hrdebutact, :hrfinact, :nomresp, :prenomresp)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'codeanim' => $codeAnim,
            'dateact' => $dateAct,
            'codeetatact' => $codeEtatAct,
            'hrrdvact' => $hrrdvAct,
            'prixact' => $prixAct,
            'hrdebutact' => $hrDebutAct,
            'hrfinact' => $hrFinAct,
            'nomresp' => $nomResp,
            'prenomresp' => $prenomResp,
        ]);
        $message = "Activité créée avec succès.";
    } catch (PDOException $e) {
        $error = "Erreur lors de la création : " . $e->getMessage();
    }
}

// Récupération des animations existantes
$animations = [];
try {
    $stmt = $pdo->query("SELECT CODEANIM, NOMANIM FROM ANIMATION");
    $animations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des animations : " . $e->getMessage();
}

// Récupération des états d'activités existants
$etatsActivite = [];
try {
    $stmt = $pdo->query("SELECT CODEETATACT, NOMETATACT FROM ETAT_ACT");
    $etatsActivite = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des états d'activités : " . $e->getMessage();
}

// Récupération des responsables existants (encadrants uniquement)
$responsables = [];
try {
    $stmt = $pdo->query("SELECT NOMCOMPTE, PRENOMCOMPTE FROM COMPTE WHERE TYPEPROFIL = 'EN'");
    $responsables = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des responsables : " . $e->getMessage();
}
?>

<?php include('../includes/header.php'); ?>
<h1>Créer une Activité</h1>

<!-- Affichage des messages -->
<?php if (isset($message)): ?>
    <p style="color: green;"><?= htmlspecialchars($message); ?></p>
<?php elseif (isset($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error); ?></p>
<?php endif; ?>

<!-- Formulaire de création d'activité -->
<form method="POST" action="">
    <label for="codeanim">Animation associée :</label>
    <select name="codeanim" id="codeanim" required>
        <option value="">-- Sélectionner une animation --</option>
        <?php foreach ($animations as $anim): ?>
            <option value="<?= htmlspecialchars($anim['CODEANIM']); ?>">
                <?= htmlspecialchars($anim['NOMANIM']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="dateact">Date de l'activité :</label>
    <input type="date" name="dateact" id="dateact" required>

    <label for="codeetatact">État de l'activité :</label>
    <select name="codeetatact" id="codeetatact" required>
        <option value="">-- Sélectionner un état --</option>
        <?php foreach ($etatsActivite as $etat): ?>
            <option value="<?= htmlspecialchars($etat['CODEETATACT']); ?>">
                <?= htmlspecialchars($etat['NOMETATACT']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="hrrdvact">Heure de rendez-vous :</label>
    <input type="time" name="hrrdvact" id="hrrdvact" required>

    <label for="prixact">Prix (€) :</label>
    <input type="number" name="prixact" id="prixact" step="0.01" required>

    <label for="hrdebutact">Heure de début :</label>
    <input type="time" name="hrdebutact" id="hrdebutact" required>

    <label for="hrfinact">Heure de fin :</label>
    <input type="time" name="hrfinact" id="hrfinact" required>


    <label for="responsable">Responsable :</label>
    <select name="responsable" id="responsable" required>
        <option value="">-- Sélectionner un responsable --</option>
        <?php foreach ($responsables as $resp): ?>
            <option value="<?= htmlspecialchars($resp['NOMCOMPTE'] . '|' . $resp['PRENOMCOMPTE']); ?>">
                <?= htmlspecialchars($resp['NOMCOMPTE'] . ' ' . $resp['PRENOMCOMPTE']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <button type="submit">Créer l'activité</button>
</form>

<?php include('../includes/footer.php'); ?>
