### Enhanced Code for Response.php

```php
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
```

### README for Response.php

**Overview:**
This section delves into the `Response.php` file, highlighting the `Response` class dedicated to managing and sending HTTP responses, including status codes, headers, and content.

**Table of Contents:**
- **Status Code Management**
- **Header Management**
- **Content Sending**

| Section                | Description                                                                                       |
|------------------------|---------------------------------------------------------------------------------------------------|
| **Status Code Management** | Details the mechanism for setting and updating the HTTP status code of the response.              |
| **Header Management**      | Explains how to add HTTP headers to the response, aiding in controlling cache, content type, etc. |
| **Content Sending**         | Describes the method for sending content back to the client, which can include HTML, JSON, or plain text. |

### File and Relationship Tracking for Response.php

- **File:** `Response.php`
  - **Purpose:** Provides a structured approach to constructing and sending HTTP responses by managing status codes, headers, and the response body.
  - **Relationships:**
    - **Router.php and Controller Classes:** These components utilize the `Response` class to send HTTP responses to the client, including setting the appropriate status codes and headers based on the outcome of the request processing.
    - **Middleware Classes:** In applications with middleware, the `Response` object can be manipulated for tasks such as adding security headers, managing content types, or handling CORS policies before the final output is sent to the client.
    - **ErrorHandling.php:** The error handling mechanisms may use the `Response` class to set appropriate HTTP status codes (e.g., 500 Internal Server Error) and send error details or generic error messages to the client.

This documentation, along with the code improvements, aims to clarify the functionalities provided by the `Response` class within the application framework, underscoring its crucial role in delivering HTTP responses to clients in a flexible and efficient manner.