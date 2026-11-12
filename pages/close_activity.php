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

// Vérification des paramètres GET
if (!isset($_GET['codeanim']) || !isset($_GET['dateact'])) {
    die("Erreur : paramètres invalides.");
}

$codeAnim = $_GET['codeanim'];
$dateAct = $_GET['dateact'];

try {
    // Vérification que l'état "AN" existe dans la table ETAT_ACT
    $stmt = $pdo->query("SELECT COUNT(*) AS count FROM ETAT_ACT WHERE CODEETATACT = 'AN'");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result['count'] == 0) {
        die("Erreur : l'état 'AN' (Annulée) n'existe pas dans la table ETAT_ACT. Veuillez vérifier la table.");
    }

    // Mise à jour pour fermer/annuler l'activité
    $sql = "UPDATE ACTIVITE SET CODEETATACT = 'AN' WHERE CODEANIM = :codeanim AND DATEACT = :dateact";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['codeanim' => $codeAnim, 'dateact' => $dateAct]);

    header("Location: list_activities.php?success=1"); // Redirection après succès
    exit;
} catch (PDOException $e) {
    die("Erreur lors de la fermeture de l'activité : " . $e->getMessage());
}
?>
