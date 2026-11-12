<?php
// Inclusion du fichier de configuration
include('../config.php');

// Démarrage de la session
session_start();

// Initialisation des messages d'erreur
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération des données du formulaire
    $username = trim($_POST['username']);
    $password = trim($_POST['password']); // Mot de passe saisi

    // Validation des champs du formulaire
    if (!empty($username) && !empty($password)) {
        try {
            // Préparer une requête pour chercher l'utilisateur
            // Find the account, then compare the typed password with the stored hash
            $sql = "SELECT * FROM COMPTE WHERE USER = :username";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['MDP'])) {
                // Connexion réussie
                $_SESSION['user_id'] = $user['USER'];
                $_SESSION['username'] = $user['NOMCOMPTE'];
                $_SESSION['prenom'] = $user['PRENOMCOMPTE'];
                $_SESSION['typeProfil'] = $user['TYPEPROFIL']; // Exemple : vacancier, encadrant, admin

                // Redirection vers le tableau de bord
                header('Location: dashboard.php');
                exit;
                
            } else {
                $error = "Nom d'utilisateur ou mot de passe incorrect.";
            }
        } catch (PDOException $e) {
            $error = "Erreur interne : " . $e->getMessage();
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<?php include('../includes/header.php'); ?>
<h1>Connexion</h1>
<form method="POST" action="">
    <label for="username">Nom d'utilisateur :</label><br>
    <input type="text" id="username" name="username" required><br><br>

    <label for="password">Mot de passe :</label><br>
    <input type="password" id="password" name="password" required><br><br>

    <button type="submit">Se connecter</button>
</form>

<?php if ($error): ?>
<p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php include('../includes/footer.php'); ?>
