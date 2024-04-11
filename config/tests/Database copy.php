<?php // config/Database.php

namespace Teammate\Config;
// namespace App\Config;

use PDO;
use PDOException;

class Database {
    private static $instance = null;

    // Prevent direct object creation and cloning
    private function __construct() {}
    private function __clone() {}

    public static function getInstance() {
        if (self::$instance === null) {
            try {
                $host = DatabaseConfig::get('host');
                $dbname = DatabaseConfig::get('dbname');
                $user = DatabaseConfig::get('user');
                $password = DatabaseConfig::get('password');

                self::$instance = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                // Log error to a file
                error_log("Database Connection Error: " . $e->getMessage(), 3, __DIR__ . "/../logs/database_error.log");
                die("Database connection failed. Check log file for details.");
            }
        }
        return self::$instance;
    }
}
