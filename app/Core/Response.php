<?php // app/Core/Response.php

// Déclaration de l'espace de noms pour la gestion des réponses HTTP
namespace App\Core;

// Définition de la classe Response pour encapsuler la gestion des réponses HTTP
class Response {
    // Statut HTTP par défaut
    protected $statusCode = 200;

    /**
     * Définit le code de statut HTTP pour la réponse.
     * 
     * @param int $code Le code de statut HTTP.
     */
    public function setStatusCode(int $code) {
        $this->statusCode = $code; // Mise à jour du code de statut
        http_response_code($this->statusCode); // Application du code de statut à la réponse
    }

    /**
     * Ajoute un en-tête HTTP à la réponse.
     * 
     * @param string $name Nom de l'en-tête.
     * @param string $value Valeur de l'en-tête.
     */
    public function addHeader(string $name, string $value) {
        header("$name: $value"); // Ajout de l'en-tête à la réponse
    }

    /**
     * Envoie le contenu spécifié au client.
     * 
     * @param string $content Le contenu à envoyer.
     */
    public function sendContent(string $content) {
        echo $content; // Affichage du contenu
    }

    // Des méthodes supplémentaires pour l'envoi de JSON, la définition de cookies, etc., peuvent être ajoutées ici
}
