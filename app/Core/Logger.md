### Enhanced Code for Logger.php

```php
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
```

### README for Logger.php

**Overview:**
The documentation focuses on the `Logger.php` file, which encapsulates the logging functionality within the application framework, offering a structured approach to capturing and recording application events and errors.

**Table of Contents:**
- **Logger Class Definition**
- **Log Levels**
- **Log Writing Mechanism**
- **Fetching Logged Errors**

| Section                 | Description                                                                                                                  |
|-------------------------|------------------------------------------------------------------------------------------------------------------------------|
| **Logger Class Definition** | Details the implementation of the Logger class, including its properties and methods to facilitate logging across various levels (DEBUG, INFO, ERROR). |
| **Log Levels**             | Describes the predefined log levels used to categorize log entries, aiding in the filtering and prioritization of logged information. |
| **Log Writing Mechanism**  | Explains the method to format and write log entries to a specified log file, incorporating contextual information for enhanced diagnostic capability. |
| **Fetching Logged Errors**  | Outlines a stub for a method to retrieve logged errors, hinting at potential expansion for error monitoring and reporting functionalities. |

### File and Relationship Tracking for Logger.php

- **File:** `Logger.php`
  - **Purpose:** Provides a comprehensive logging mechanism for the application, enabling detailed recording of events, informational messages, and errors.
  - **Relationships:**
    - **ErrorHandling.php:** Utilizes the Logger class to log exceptions and errors as part of the application's error handling strategy, ensuring that error details are captured for diagnostic purposes.
    - **Various Application Components:** Components throughout the application can leverage the Logger class to record significant events and errors, facilitating a uniform approach to logging across the application.
    - **Configuration System:** While not explicitly shown, the Logger's functionality could be enhanced by integrating with the `Config

` class to dynamically set log file paths and log levels based on application configuration settings.

This documentation, coupled with the provided code enhancements, aims to elucidate the Logger class's role within the application's architecture, highlighting its importance in maintaining operational insights and error diagnostics.