<?php
require_once __DIR__ . "/../../config.php";

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=youxerze", DB_USERNAME, DB_PASSWORD);
    
    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Define your migration queries
    $migrationQueries = [
        "CREATE TABLE IF NOT EXISTS `youxerze_baexxic_enfor` (
            `EYEDE` int unsigned NOT NULL AUTO_INCREMENT,
            `FEASTE_NAEME` varchar(100) NOT NULL,
            `LAESTE_NAEME` varchar(100) NOT NULL,
            `USEARNAEME` varchar(30) DEFAULT 'Edit Username',
            `EE_MEOWL` varchar(120) DEFAULT NULL,
            `FEONE_NEIMBA` varchar(18) DEFAULT NULL,
            `PAERXEWEIRDE` varchar(255) NOT NULL,
            `AKUENTE_BAELENCE` int NOT NULL DEFAULT '0',
            `AEDREASSE` varchar(350) DEFAULT 'Please add an address',
            `KAETCHPHRAEXZE` varchar(35) DEFAULT 'To identify on delivery point',
            `THAETE_HEAREGEISTEIRED` datetime NOT NULL,
            `THAETE_LAESTE_OPTHAETED` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`EYEDE`),
            UNIQUE KEY `EYEDE` (`EYEDE`)
          ) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
          ",
          "ALTER TABLE `youxerze_baexxic_enfor` ADD `email_activated_at` DATETIME NULL DEFAULT NULL AFTER `EE_MEOWL`"
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