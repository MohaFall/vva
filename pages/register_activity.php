<?php
// Démarrage de la session
session_start();

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php'); // Redirection vers la page de connexion
    exit;
}

// Inclusion du fichier de configuration
include('../config.php');

// Vérification du paramètre GET
if (!isset($_GET['code']) || empty($_GET['code'])) {
    die("Erreur : aucun code d'activité spécifié.");
}

// Récupération des informations sur l'activité
$codeAnim = $_GET['code'];
$activity = [];
try {
    $sql = "SELECT act.CODEANIM, act.DATEACT, act.HRRDVACT, anim.NBREPLACEANIM 
            FROM ACTIVITE act
            INNER JOIN ANIMATION anim ON act.CODEANIM = anim.CODEANIM
            WHERE act.CODEANIM = :codeanim";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['codeanim' => $codeAnim]);
    $activity = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$activity) {
        die("Erreur : activité non trouvée.");
    }
} catch (PDOException $e) {
    die("Erreur lors de la récupération de l'activité : " . $e->getMessage());
}

// Vérification des places disponibles
if ($activity['NBREPLACEANIM'] <= 0) {
    die("Désolé, il n'y a plus de places disponibles pour cette activité.");
}

// Inscription à l'activité
try {
    // Ajout de l'inscription dans la table INSCRIPTION
    $sql = "INSERT INTO INSCRIPTION (USER, CODEANIM, DATEACT, DATEINSCRIP) 
            VALUES (:user, :codeanim, :dateact, :dateinscrip)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'user' => $_SESSION['user_id'],
        'codeanim' => $codeAnim,
        'dateact' => $activity['DATEACT'],
        'dateinscrip' => date('Y-m-d'),
    ]);

            


    // Mise à jour des places disponibles
    $sqlUpdate = "UPDATE ANIMATION SET NBREPLACEANIM = NBREPLACEANIM - 1 WHERE CODEANIM = :codeanim";
    $stmtUpdate = $pdo->prepare($sqlUpdate);
    $stmtUpdate->execute(['codeanim' => $codeAnim]);

    echo "Inscription réussie ! Vous êtes maintenant inscrit à cette activité.";
} catch (PDOException $e) {
    die("Erreur lors de l'inscription : " . $e->getMessage());
}
?>
<a href="activities.php">Retour à la liste des activités</a>
