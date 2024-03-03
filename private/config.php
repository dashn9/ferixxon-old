<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Load Composer's autoloader

use Dotenv\Dotenv;

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__ . "/../");
$dotenv->load();

# AWS
define('AWS_KEY', $_ENV['AWS_KEY']);
define('AWS_SECRET', $_ENV['AWS_SECRET']);
define('AWS_REGION', $_ENV['AWS_REGION']);


define('DB_HOST', $_ENV['DB_HOST']);
define('DB_DATABASE', $_ENV['DB_DATABASE']);

define('DB_USERNAME', $_ENV['DB_ROOT_USERNAME']);
define('DB_PASSWORD', $_ENV['DB_ROOT_PASSWORD']);

?>