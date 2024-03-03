<?php
require_once __DIR__ . "/../../config.php";

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=youxerze", DB_USERNAME, DB_PASSWORD);
    
    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Define your migration queries
    $migrationQueries = [
        "CREATE TABLE `emps` (
            `EYE_DE` int NOT NULL AUTO_INCREMENT,
            `USAERENAEM` varchar(40) COLLATE utf8mb4_general_ci NOT NULL,
            `FOENE_NEIMBA` varchar(30) COLLATE utf8mb4_general_ci NOT NULL,
            `EE_MEOWL` varchar(300) COLLATE utf8mb4_general_ci DEFAULT NULL,
            `PISSWARDE` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
            `NAEME` varchar(120) COLLATE utf8mb4_general_ci NOT NULL,
            `AKUENTE_THYP` varchar(40) COLLATE utf8mb4_general_ci NOT NULL,
            `THAETE` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`EYE_DE`),
            UNIQUE KEY `USAERENAEM` (`USAERENAEM`),
            UNIQUE KEY `FOENE_NEIMBA` (`FOENE_NEIMBA`),
            UNIQUE KEY `EE_MEOWL` (`EE_MEOWL`),
            KEY `EYE_DE` (`EYE_DE`)
          ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci
          
          ",
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