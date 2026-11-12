<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['typeProfil'] !== 'EN') {
    header('Location: login.php');
    exit;
}

include('../config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codeAnim = $_POST['codeanim'];
    $codeTypeAnim = $_POST['codetypeanim'];
    $nomAnim = $_POST['nomanim'];
    $dateCreation = $_POST['datecreationanim'];
    $dateValidite = $_POST['datevaliditeanim'];
    $dureeAnim = floatval($_POST['dureeanime']);
    $limiteAge = intval($_POST['limiteage']);
    $tarifAnim = floatval($_POST['tarifanim']);
    $nbrePlace = intval($_POST['nbreplaceanim']);
    $descriptAnim = $_POST['descriptanim'];
    $commentAnim = $_POST['commentanim'];
    $difficulte = $_POST['difficulteanim'];

    try {
        $sql = "INSERT INTO ANIMATION (CODEANIM, CODETYPEANIM, NOMANIM, DATECREATIONANIM, DATEVALIDITEANIM, DUREEANIM, LIMITEAGE, TARIFANIM, NBREPLACEANIM, DESCRIPTANIM, COMMENTANIM, DIFFICULTEANIM) 
                VALUES (:codeanim, :codetypeanim, :nomanim, :datecreationanim, :datevaliditeanim, :dureeanime, :limiteage, :tarifanim, :nbreplaceanim, :descriptanim, :commentanim, :difficulteanim)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'codeanim' => $codeAnim,
            'codetypeanim' => $codeTypeAnim,
            'nomanim' => $nomAnim,
            'datecreationanim' => $dateCreation,
            'datevaliditeanim' => $dateValidite,
            'dureeanime' => $dureeAnim,
            'limiteage' => $limiteAge,
            'tarifanim' => $tarifAnim,
            'nbreplaceanim' => $nbrePlace,
            'descriptanim' => $descriptAnim,
            'commentanim' => $commentAnim,
            'difficulteanim' => $difficulte,
        ]);
        $message = "Animation créée avec succès.";
    } catch (PDOException $e) {
        $error = "Erreur lors de la création : " . $e->getMessage();
    }
}

$typeAnimations = [];
try {
    $stmt = $pdo->query("SELECT CODETYPEANIM, NOMTYPEANIM FROM type_anim");
    $typeAnimations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des types d'animations : " . $e->getMessage();
}
?>

<?php include('../includes/header.php'); ?>
<h1>Créer une Animation</h1>

<?php if (isset($message)): ?>
    <p style="color: green;"><?= htmlspecialchars($message); ?></p>
<?php elseif (isset($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error); ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label for="codeanim">Code de l'animation :</label>
    <input type="text" name="codeanim" id="codeanim" maxlength="8" required>

    <label for="codetypeanim">Type d'animation :</label>
    <select name="codetypeanim" id="codetypeanim" required>
        <option value="">-- Sélectionner un type --</option>
        <?php foreach ($typeAnimations as $type): ?>
            <option value="<?= htmlspecialchars($type['CODETYPEANIM']); ?>">
                <?= htmlspecialchars($type['NOMTYPEANIM']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="nomanim">Nom de l'animation :</label>
    <input type="text" name="nomanim" id="nomanim" required>

    <label for="datecreationanim">Date de création :</label>
    <input type="date" name="datecreationanim" id="datecreationanim" required>

    <label for="datevaliditeanim">Date de validité :</label>
    <input type="date" name="datevaliditeanim" id="datevaliditeanim" required>

    <label for="dureeanime">Durée (en heures) :</label>
    <input type="number" name="dureeanime" id="dureeanime" step="0.1" required>

    <label for="limiteage">Limite d'âge :</label>
    <input type="number" name="limiteage" id="limiteage" required>

    <label for="tarifanim">Tarif (€) :</label>
    <input type="number" name="tarifanim" id="tarifanim" step="0.01" required>

    <label for="nbreplaceanim">Nombre de places :</label>
    <input type="number" name="nbreplaceanim" id="nbreplaceanim" required>

    <label for="descriptanim">Description :</label>
    <textarea name="descriptanim" id="descriptanim" required></textarea>

    <label for="commentanim">Commentaire :</label>
    <textarea name="commentanim" id="commentanim"></textarea>

    <label for="difficulteanim">Difficulté :</label>
    <input type="text" name="difficulteanim" id="difficulteanim">

    <button type="submit">Créer l'animation</button>
</form>

<?php include('../includes/footer.php'); ?>
