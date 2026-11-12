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

// Gestion de la soumission du formulaire pour créer ou modifier une animation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $codeAnim = $_POST['codeanim'] ?? null;
    $nomAnim = $_POST['nomanim'];
    $descAnim = $_POST['descanim'];
    $places = intval($_POST['placesanim']);

    try {
        if ($codeAnim) {
            // Modification d'une animation existante
            $sql = "UPDATE ANIMATION SET NOMANIM = :nomanim, DESCRIPTANIM = :descanim, NBREPLACEANIM = :places WHERE CODEANIM = :codeanim";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'nomanim' => $nomAnim,
                'descanim' => $descAnim,
                'places' => $places,
                'codeanim' => $codeAnim,
            ]);
            $message = "Animation modifiée avec succès.";
        } else {
            // Création d'une nouvelle animation
            $sql = "INSERT INTO ANIMATION (NOMANIM, DESCRIPTANIM, NBREPLACEANIM) VALUES (:nomanim, :descanim, :places)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'nomanim' => $nomAnim,
                'descanim' => $descAnim,
                'places' => $places,
            ]);
            $message = "Animation créée avec succès.";
        }
    } catch (PDOException $e) {
        $error = "Erreur lors de la sauvegarde : " . $e->getMessage();
    }
}

// Récupération des animations existantes pour modification
$animations = [];
try {
    $stmt = $pdo->query("SELECT * FROM ANIMATION");
    $animations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Erreur lors de la récupération des animations : " . $e->getMessage();
}
?>

<?php include('../includes/header.php'); ?>
<h1>Gestion des animations</h1>

<!-- Affichage des messages -->
<?php if (isset($message)): ?>
    <p style="color: green;"><?= htmlspecialchars($message); ?></p>
<?php elseif (isset($error)): ?>
    <p style="color: red;"><?= htmlspecialchars($error); ?></p>
<?php endif; ?>

<!-- Formulaire pour créer ou modifier une animation -->
<form method="POST" action="">
    <label for="codeanim">Code de l'animation (laisser vide pour créer) :</label>
    <input type="text" name="codeanim" id="codeanim" maxlength="10">

    <label for="nomanim">Nom de l'animation :</label>
    <input type="text" name="nomanim" id="nomanim" required>

    <label for="descanim">Description :</label>
    <textarea name="descanim" id="descanim" required></textarea>

    <label for="placesanim">Nombre de places :</label>
    <input type="number" name="placesanim" id="placesanim" required>

    <button type="submit">Sauvegarder</button>
</form>

<!-- Liste des animations -->
<h2>Animations existantes</h2>
<table border="1">
    <thead>
        <tr>
            <th>Code</th>
            <th>Nom</th>
            <th>Description</th>
            <th>Places disponibles</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($animations as $animation): ?>
            <tr>
                <td><?= htmlspecialchars($animation['CODEANIM']); ?></td>
                <td><?= htmlspecialchars($animation['NOMANIM']); ?></td>
                <td><?= htmlspecialchars($animation['DESCRIPTANIM']); ?></td>
                <td><?= htmlspecialchars($animation['NBREPLACEANIM']); ?></td>
                
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include('../includes/footer.php'); ?>
