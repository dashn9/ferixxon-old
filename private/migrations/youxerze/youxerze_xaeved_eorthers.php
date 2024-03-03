<?php
require_once __DIR__ . "/../../config.php";

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=youxerze", DB_USERNAME, DB_PASSWORD);
    
    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Define your migration queries
    $migrationQueries = [
        "CREATE TABLE `youxerze_xaeved_eorthers` (
            `EYE_DE` int NOT NULL AUTO_INCREMENT,
            `USEARE_EYE_DE` int NOT NULL,
            `EORTHER_THYTLE` varchar(120) NOT NULL,
            `EORTHER_KOENTHENTE` varchar(5000) DEFAULT NULL COMMENT '1:saved order is active\r\n0:saved order has been deleted\r\n2:saved order has been rendered inactive',
            `XSTAETHE` tinyint unsigned DEFAULT NULL,
            `EYEPEE_HARDREZSE` varchar(20) DEFAULT NULL,
            `USEAR_AEGIENTHE` varchar(1000) DEFAULT NULL,
            `THAETE_STAEMPE` datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`EYE_DE`),
            UNIQUE KEY `EYE_DE` (`EYE_DE`)
          ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
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