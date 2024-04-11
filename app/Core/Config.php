<?php // app/Core/Config.php

// Utilisation de l'espace de noms pour structurer le gestionnaire de configuration
namespace App\Core;

/*
Pseudocode:
Charger le fichier de configuration spécifié.
Récupérer une valeur de configuration en fonction de la section et de la clé demandées.
*/

// Définition de la classe Config pour gérer les configurations de l'application
class Config {
    // Tableau associatif pour stocker les paramètres de configuration
    private static $settings = [];

    // Charge le fichier de configuration et stocke les paramètres
    public static function load($file) {
        // Vérifie si le fichier de configuration existe
        if (file_exists($file)) {
            // Charge et analyse le fichier ini, stocke les résultats dans $settings
            self::$settings = parse_ini_file($file, true);
        } else {
            // Lève une exception si le fichier de configuration n'est pas trouvé
            throw new \Exception("Fichier de configuration non trouvé : $file");
        }
    }

    // Récupère une valeur de configuration à partir de la section et de la clé spécifiées
    public static function get($section, $key, $default = null) {
        // Retourne la valeur de configuration si elle existe, sinon retourne la valeur par défaut
        return self::$settings[$section][$key] ?? $default;
    }
}
