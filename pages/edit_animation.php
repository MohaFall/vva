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

// Récupération de l'animation à modifier
if (!isset($_GET['codeanim']) || empty($_GET['codeanim'])) {
    die("Erreur : aucun code d'animation fourni.");
}

$codeAnim = $_GET['codeanim'];
try {
    $stmt = $pdo->prepare("SELECT * FROM ANIMATION WHERE CODEANIM = :codeanim");
    $stmt->execute(['codeanim' => $codeAnim]);
    $animation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$animation) {
        die("Erreur : animation introuvable.");
    }
} catch (PDOException $e) {
    die("Erreur lors de la récupération de l'animation : " . $e->getMessage());
}

// Gestion de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nomAnim = $_POST['nomanim'];
    $descAnim = $_POST['descriptanim'];
    $places = intval($_POST['nbreplaceanim']);
    $duree = floatval($_POST['dureeanime']);
    $ageLimite = intval($_POST['limiteage']);
    $tarif = floatval($_POST['tarifanim']);
    $comment = $_POST['commentanim'];
    $difficulte = $_POST['difficulteanim'];
    $dateCreation = $_POST['datecreationanim'];
    $dateValidite = $_POST['datevaliditeanim'];

    try {
        // Modification de l'animation
        $sql = "UPDATE ANIMATION 
                SET NOMANIM = :nomanim, DESCRIPTANIM = :descriptanim, NBREPLACEANIM = :nbreplaceanim, 
                    DUREEANIM = :dureeanime, LIMITEAGE = :limiteage, TARIFANIM = :tarifanim, 
                    COMMENTANIM = :commentanim, DIFFICULTEANIM = :difficulteanim, 
                    DATECREATIONANIM = :datecreationanim, DATEVALIDITEANIM = :datevaliditeanim
                WHERE CODEANIM = :codeanim";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'nomanim' => $nomAnim,
            'descriptanim' => $descAnim,
            'nbreplaceanim' => $places,
            'dureeanime' => $duree,
            'limiteage' => $ageLimite,
            'tarifanim' => $tarif,
            'commentanim' => $comment,
            'difficulteanim' => $difficulte,
            'datecreationanim' => $dateCreation,
            'datevaliditeanim' => $dateValidite,
            'codeanim' => $codeAnim,
        ]);
        $message = "Animation modifiée avec succès.";
    } catch (PDOException $e) {
        $error = "Erreur lors de la modification : " . $e->getMessage();
    }
}
?>

<?php include('../includes/header.php'); ?>
<h1>Modifier une Animation</h1>

<!-- Affichage des messages -->
<?php if (isset($message)): ?>
    <p style="color: green;"><?= htmlspecialchars($message); ?></p>
<?php elseif (isset($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error); ?></p>
<?php endif; ?>

<!-- Formulaire de modification d'animation -->
<form method="POST" action="">
    <label for="nomanim">Nom de l'animation :</label>
    <input type="text" name="nomanim" id="nomanim" value="<?= htmlspecialchars($animation['NOMANIM']); ?>" required>

    <label for="descriptanim">Description :</label>
    <textarea name="descriptanim" id="descriptanim" required><?= htmlspecialchars($animation['DESCRIPTANIM']); ?></textarea>

    <label for="nbreplaceanim">Nombre de places :</label>
    <input type="number" name="nbreplaceanim" id="nbreplaceanim" value="<?= htmlspecialchars($animation['NBREPLACEANIM']); ?>" required>

    <label for="dureeanime">Durée (en heures) :</label>
    <input type="number" name="dureeanime" id="dureeanime" step="0.1" value="<?= htmlspecialchars($animation['DUREEANIM']); ?>" required>

    <label for="limiteage">Limite d'âge :</label>
    <input type="number" name="limiteage" id="limiteage" value="<?= htmlspecialchars($animation['LIMITEAGE']); ?>" required>

    <label for="tarifanim">Tarif (€) :</label>
    <input type="number" name="tarifanim" id="tarifanim" step="0.01" value="<?= htmlspecialchars($animation['TARIFANIM']); ?>" required>

    <label for="datecreationanim">Date de création :</label>
    <input type="date" name="datecreationanim" id="datecreationanim" value="<?= htmlspecialchars($animation['DATECREATIONANIM']); ?>" required>

    <label for="datevaliditeanim">Date de validité :</label>
    <input type="date" name="datevaliditeanim" id="datevaliditeanim" value="<?= htmlspecialchars($animation['DATEVALIDITEANIM']); ?>" required>

    <label for="commentanim">Commentaire :</label>
    <textarea name="commentanim" id="commentanim"><?= htmlspecialchars($animation['COMMENTANIM']); ?></textarea>

    <label for="difficulteanim">Difficulté :</label>
    <input type="text" name="difficulteanim" id="difficulteanim" value="<?= htmlspecialchars($animation['DIFFICULTEANIM']); ?>">

    <button type="submit">Modifier l'animation</button>
</form>

<?php include('../includes/footer.php'); ?>