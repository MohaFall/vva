<?php
// Inclusion du fichier de configuration
include('../config.php');

// Initialisation des messages
$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération des données du formulaire
    $user = trim($_POST['user']);
    $password = trim($_POST['password']);
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $typeProfil = 'VA'; // Par défaut, type "vacancier"

    // Validation des champs
    if (!empty($user) && !empty($password) && !empty($nom) && !empty($prenom)) {
        try {
            // Vérifier si l'utilisateur existe déjà
            $sql_check = "SELECT * FROM COMPTE WHERE USER = :user";
            $stmt_check = $pdo->prepare($sql_check);
            $stmt_check->execute(['user' => $user]);

            if ($stmt_check->rowCount() > 0) {
                $error = "Cet identifiant est déjà utilisé. Veuillez en choisir un autre.";
            } else {
                // Insérer l'utilisateur dans la base de données
                $sql_insert = "INSERT INTO COMPTE (USER, MDP, NOMCOMPTE, PRENOMCOMPTE, TYPEPROFIL) 
                               VALUES (:user, :password, :nom, :prenom, :typeProfil)";
                $stmt_insert = $pdo->prepare($sql_insert);
                $stmt_insert->execute([
                    'user' => $user,
                    'password' => password_hash($password, PASSWORD_DEFAULT), // only the hash is stored
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'typeProfil' => $typeProfil,
                ]);

                $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
            }
        } catch (PDOException $e) {
            $error = "Erreur interne : " . $e->getMessage();
        }
    } else {
        $error = "Tous les champs sont obligatoires.";
    }
}
?>

<?php include('../includes/header.php'); ?>
<h1>Inscription</h1>
<form method="POST" action="">
    <label for="user">Identifiant (USER) :</label><br>
    <input type="text" id="user" name="user" required><br><br>

    <label for="password">Mot de passe :</label><br>
    <input type="password" id="password" name="password" required><br><br>

    <label for="nom">Nom :</label><br>
    <input type="text" id="nom" name="nom" required><br><br>

    <label for="prenom">Prénom :</label><br>
    <input type="text" id="prenom" name="prenom" required><br><br>

    <button type="submit">S'inscrire</button>
</form>

<?php if ($success): ?>
<p style="color: green;"><?= htmlspecialchars($success) ?></p>
<?php endif; ?>

<?php if ($error): ?>
<p style="color: red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<p>Déjà un compte ? <a href="login.php">Connectez-vous ici</a>.</p>
<?php include('../includes/footer.php'); ?>
