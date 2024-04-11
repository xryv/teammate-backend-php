<?php
// app/Core/Logger.php

// Déclaration de l'espace de noms pour la gestion de logs
namespace App\Core;

// Définition de la classe Logger pour gérer les logs de l'application
class Logger {
    // Déclaration des niveaux de logs disponibles
    const DEBUG = 'DEBUG';
    const INFO = 'INFO';
    const ERROR = 'ERROR';

    // Chemin vers le fichier de log
    protected $logFile;

    /**
     * Constructeur pour initialiser le fichier de logs.
     * 
     * @param string $logFile Chemin vers le fichier de logs
     */
    public function __construct($logFile = __DIR__ . '/../../logs/app.log') {
        $this->logFile = $logFile;
    }

    /**
     * Généraliser la fonction de logging pour différents niveaux de logs.
     * 
     * @param string $level Niveau de log (DEBUG, INFO, ERROR)
     * @param string $message Message de log
     * @param array $context Contexte supplémentaire pour le log
     */
    public function log($level, $message, array $context = []) {
        // Formatage de l'entrée de log avec la date, le niveau et le contexte
        $logEntry = sprintf(
            "[%s] [%s]: %s %s\n",
            date('Y-m-d H:i:s'), // Date et heure actuelle
            strtoupper($level), // Niveau de log
            $message, // Message de log
            json_encode($context) // Contexte en format JSON
        );

        // Écriture de l'entrée de log dans le fichier
        file_put_contents($this->logFile, $logEntry, FILE_APPEND | LOCK_EX);
    }

    // Fonctions de commodité pour chaque niveau de log

    public function debug($message, array $context = []) {
        $this->log(self::DEBUG, $message, $context);
    }

    public function info($message, array $context = []) {
        $this->log(self::INFO, $message, $context);
    }

    public function error($message, array $context = []) {
        $this->log(self::ERROR, $message, $context);
    }

    /**
     * Récupère les erreurs loggées dans le système.
     * 
     * @return array Tableau des messages d'erreur.
     */
    public static function getLoggedErrors(): array {
        // Implémenter la logique de récupération des erreurs loggées ici
        // Par exemple, lecture des logs d'erreurs depuis un fichier ou une base de données
        return []; // Valeur de retour place-holder
    }

    // Méthodes additionnelles pour d'autres niveaux de logs ou gestionnaires selon les besoins
}

// Exemple d'utilisation:
// $logger = new Logger();
// $logger->info('Utilisateur connecté', ['username' => 'john_doe']);
