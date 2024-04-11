<?php // app/Core/Database.php

namespace App\Core;

use PDO;
use PDOException;
use Teammate\Config\DatabaseConfig; // Importer la configuration de la base de données

/*
Pseudocode:
- Utiliser le modèle Singleton pour gérer une unique instance de connexion PDO.
- Vérifier l'existence de l'instance PDO avant de la créer, en utilisant les paramètres fournis par DatabaseConfig.
- Gérer les exceptions lors de la tentative de connexion.
- Retourner l'instance PDO pour les opérations de base de données.
*/

// Classe pour gérer la connexion à la base de données via PDO
class Database {
    private static $instance = null; // Instance unique PDO

    // Constructeur privé pour empêcher l'instanciation directe
    private function __construct() {}
    // Prévenir le clonage de l'instance
    private function __clone() {}

    // Obtenir l'instance PDO, la créer si elle n'existe pas
    public static function getInstance(): PDO {
        if (self::$instance === null) {
            try {
                // Récupérer les paramètres de connexion via DatabaseConfig
                $host = DatabaseConfig::get('host');
                $dbname = DatabaseConfig::get('dbname');
                $user = DatabaseConfig::get('user');
                $password = DatabaseConfig::get('password');

                // Créer l'instance PDO avec les paramètres de connexion
                self::$instance = new PDO(
                    "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
                    $user,
                    $password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    ]
                );
            } catch (PDOException $e) {
                // Gérer l'exception et potentiellement logger l'erreur
                throw new \Exception("Échec de la connexion à la base de données : " . $e->getMessage());
            }
        }
        return self::$instance; // Retourner l'instance PDO
    }
}
