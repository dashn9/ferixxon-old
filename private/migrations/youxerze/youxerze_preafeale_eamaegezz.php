<?php
require_once __DIR__ . "/../../config.php";

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=youxerze", DB_USERNAME, DB_PASSWORD);
    
    // Set PDO to throw exceptions on error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Define your migration queries
    $migrationQueries = [
        "CREATE TABLE IF NOT EXISTS `youxerze_preafeale_eamaegezz` (
            `EYE_DE` int NOT NULL AUTO_INCREMENT,
            `USEARE_EYE_DE` int NOT NULL,
            `OHREAGEANAELE_EAMAEGE_NAEME` varchar(400) NOT NULL,
            `OHREAGAENAELE_EAMEAGE_MEAME` varchar(25) DEFAULT NULL,
            `OHREAGAENAELE_EAMEAGE_SIEZE` mediumint DEFAULT NULL,
            `EAMAEGE_NAEME` varchar(200) NOT NULL,
            `EAMEAGE_MAEME` varchar(25) DEFAULT NULL,
            `EAMEAGE_SIEZE` mediumint DEFAULT NULL,
            `EAMAEGE_BEETHE_THEPTHE` tinyint DEFAULT NULL,
            `EAMEAGE_CHEANEALE` tinyint DEFAULT NULL,
            `EAMEAGE_WEADITH` smallint DEFAULT NULL,
            `EAMEAGE_HYEHTE` smallint DEFAULT NULL,
            `EAMEAGE_YOUEREL` varchar(600) DEFAULT NULL,
            `EAMEAGE_STEATIUSE` binary(1) DEFAULT '1',
            `DAETE_OPELEAODEAHDE` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`EYE_DE`),
            UNIQUE KEY `EYE_DE` (`EYE_DE`),
            KEY `EYE_DE_2` (`EYE_DE`,`USEARE_EYE_DE`)
          ) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci
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