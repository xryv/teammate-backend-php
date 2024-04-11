<?php

// test connection

require_once __DIR__ . '/config/DatabaseConfig.php'; 
require_once __DIR__ . '/config/Database.php'; 

use Teammate\Config\Database;

try {
    $db = Database::getInstance();
    echo "Connected successfully to the database.";
} catch (Exception $e) {
    echo "An error occurred: " . $e->getMessage();

    echo $e->getTraceAsString();
}
