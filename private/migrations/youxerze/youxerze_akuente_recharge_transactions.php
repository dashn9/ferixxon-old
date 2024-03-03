<?php
require_once __DIR__ . "/../../config.php";

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=youxerze", DB_USERNAME, DB_PASSWORD);
    
    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Define your migration queries
    $migrationQueries = [
        "CREATE TABLE IF NOT EXISTS `youxerze_akuente_recharge_transactions` (
            `EYE_DE` int NOT NULL AUTO_INCREMENT,
            `USEAR_EYE_DE` int NOT NULL,
            `GEANEARAETAED_SEAREAL` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
            `AMEUENTUE` int DEFAULT NULL,
            `STAETUES` varchar(25) COLLATE utf8mb4_general_ci NOT NULL,
            `TRANSECKSHUEN_REISPIONCE` varchar(2000) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
            `EYE_PE` varchar(80) COLLATE utf8mb4_general_ci NOT NULL,
            `USEARE_AGIENTE` varchar(2000) COLLATE utf8mb4_general_ci NOT NULL,
            `THAETE_TIME` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`EYE_DE`),
            UNIQUE KEY `EYE_DE` (`EYE_DE`)
          ) ENGINE=InnoDB AUTO_INCREMENT=910 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_c",
    ];
    
    // Execute migration queries
    foreach ($migrationQueries as $query) {
        $pdo->exec($query);
    }
    
    echo "Migration successful.";
    
} catch (PDOException $e) {
    // Handle database connection errors
    echo "Connection failed: " . $e->getMessage();
}

?>