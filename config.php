<?php
// Database settings are read from the .env file (ignored by Git), never written in the code.
$env = parse_ini_file(__DIR__ . '/.env');
if ($env === false) {
    die("Fichier .env introuvable : copiez .env.example en .env puis remplissez-le.");
}

try {
    $pdo = new PDO(
        "mysql:host={$env['DB_HOST']};dbname={$env['DB_NAME']};charset=utf8mb4",
        $env['DB_USER'],
        $env['DB_PASSWORD']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}
