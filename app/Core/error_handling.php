<?php
// app/Core/ErrorHandling.php

// Déclaration de l'espace de noms pour la gestion des erreurs
namespace App\Core;

// Utilisation de la classe ErrorException pour les erreurs
use ErrorException;

/*
Pseudocode:
Définir un gestionnaire d'exceptions global pour traiter toutes les exceptions non capturées.
Définir un gestionnaire d'erreurs global pour convertir les erreurs PHP en exceptions.
Enregistrer une fonction de terminaison pour gérer les erreurs fatales.
Fournir une fonction pour logger les erreurs.
Fournir une fonction pour afficher une page d'erreur générique.
*/

// Classe ValidationException personnalisée pour gérer les exceptions de validation
class ValidationException extends \Exception {}

// Gestionnaire d'exceptions global
set_exception_handler(function ($exception) {
    // Logger l'exception
    logError("Exception : " . $exception->getMessage());
    // Afficher une page d'erreur générique
    require __DIR__ . '/../Views/error.php';
});

// Gestionnaire d'erreurs global
set_error_handler(function ($severity, $message, $file, $line) {
    // Convertir les erreurs en exceptions ErrorException
    throw new ErrorException($message, 0, $severity, $file, $line);
});

// Fonction de terminaison pour les erreurs fatales
register_shutdown_function(function () {
    $error = error_get_last(); // Récupérer la dernière erreur
    // Vérifier si l'erreur est fatale
    if ($error !== null && ($error['type'] === E_ERROR || $error['type'] === E_PARSE || $error['type'] === E_CORE_ERROR || $error['type'] === E_COMPILE_ERROR)) {
        // Logger l'erreur fatale
        logError("Erreur fatale : {$error['message']} dans {$error['file']} à la ligne {$error['line']}");
        // Afficher une page d'erreur générique
        require __DIR__ . '/../Views/error.php';
    }
});

/**
 * Logger un message d'erreur dans un fichier log dédié.
 *
 * @param string $message Le message d'erreur à logger.
 */
function logError(string $message): void {
    // Personnaliser le chemin et le format du log si nécessaire
    error_log($message, 3, __DIR__ . '/../../logs/error.log');
}

/**
 * Afficher une page d'erreur générique à l'utilisateur.
 */
function displayErrorPage(): void {
    http_response_code(500); // Définir le code de réponse HTTP à 500
    require __DIR__ . '/../Views/error.php'; // Chemin vers une page d'erreur générique
    exit;
}
