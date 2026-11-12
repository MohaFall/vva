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

// Vérification des paramètres GET
if (!isset($_GET['action'], $_GET['code'], $_GET['date']) || empty($_GET['action']) || empty($_GET['code']) || empty($_GET['date'])) {
    die("Erreur : paramètres invalides.");
}

$action = $_GET['action'];
$codeAnim = $_GET['code'];
$dateAct = $_GET['date'];

// Déterminer la redirection par défaut ou spécifique
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'activities.php';

try {
    if ($action === 'register') {
        // Inscription
        $sql = "INSERT INTO INSCRIPTION (USER, CODEANIM, DATEACT, DATEINSCRIP) 
                VALUES (:user, :codeanim, :dateact, :dateinscrip)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'user' => $_SESSION['user_id'],
            'codeanim' => $codeAnim,
            'dateact' => $dateAct,
            'dateinscrip' => date('Y-m-d'),
        ]);

        //Mis à jour du nombre d'inscription à cette activité
        $sqlUpdate = "UPDATE activite SET nbInscription = nbInscription + 1 WHERE CODEANIM = :codeanim AND DATEACT = :dateact";
        $stmt = $pdo->prepare($sqlUpdate);
        $stmt->execute([
            'codeanim' => $codeAnim,
            'dateact' => $dateAct,
        ]);

        // Mise à jour des places disponibles
        $sqlUpdate = "UPDATE ANIMATION SET NBREPLACEANIM = NBREPLACEANIM - 1 WHERE CODEANIM = :codeanim";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->execute(['codeanim' => $codeAnim]);

        echo "Inscription réussie !";
    } elseif ($action === 'unregister') {
        // Désinscription
        $sql = "DELETE FROM INSCRIPTION WHERE USER = :user AND CODEANIM = :codeanim AND DATEACT = :dateact";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'user' => $_SESSION['user_id'],
            'codeanim' => $codeAnim,
            'dateact' => $dateAct,
        ]);

        //Mis à jour du nombre d'inscription à cette activité
        $sqlUpdate = "UPDATE activite SET nbInscription = nbInscription - 1 WHERE CODEANIM = :codeanim AND DATEACT = :dateact";
        $stmt = $pdo->prepare($sqlUpdate);
        $stmt->execute([
            'codeanim' => $codeAnim,
            'dateact' => $dateAct,
        ]);

        // Mise à jour des places disponibles
        $sqlUpdate = "UPDATE ANIMATION SET NBREPLACEANIM = NBREPLACEANIM + 1 WHERE CODEANIM = :codeanim";
        $stmtUpdate = $pdo->prepare($sqlUpdate);
        $stmtUpdate->execute(['codeanim' => $codeAnim]);

        echo "Désinscription réussie !";
    } else {
        die("Erreur : action non reconnue.");
    }
} catch (PDOException $e) {
    die("Erreur lors du traitement : " . $e->getMessage());
}

// Redirection vers la page appropriée
header("Location: $redirect");
exit;
