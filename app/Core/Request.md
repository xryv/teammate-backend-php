### Enhanced Code for Request.php

```php
<?php // app/Core/Request.php

// Déclaration de l'espace de noms pour la gestion des requêtes
namespace App\Core;

// Définition de la classe Request pour encapsuler les informations de requête
class Request {
    /**
     * Récupère une valeur GET à partir de la clé spécifiée.
     * 
     * @param string $key La clé de la valeur GET à récupérer.
     * @param mixed $default La valeur par défaut si la clé n'est pas trouvée.
     * @return mixed La valeur de la clé GET ou la valeur par défaut.
     */
    public function get($key, $default = null) {
        return $_GET[$key] ?? $default;
    }

    /**
     * Récupère une valeur POST à partir de la clé spécifiée.
     * 
     * @param string $key La clé de la valeur POST à récupérer.
     * @param mixed $default La valeur par défaut si la clé n'est pas trouvée.
     * @return mixed La valeur de la clé POST ou la valeur par défaut.
     */
    public function post($key, $default = null) {
        return $_POST[$key] ?? $default;
    }

    /**
     * Vérifie si une clé spécifique existe dans les données POST.
     * 
     * @param string $key La clé à vérifier dans les données POST.
     * @return bool Vrai si la clé existe, faux sinon.
     */
    public function hasPost($key): bool {
        return isset($_POST[$key]);
    }

    /**
     * Récupère la méthode HTTP de la requête courante.
     * 
     * @return string La méthode HTTP de la requête.
     */
    public function method() {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Récupère l'URI de la requête courante.
     * 
     * @return string L'URI de la requête.
     */
    public function uri() {
        return $_SERVER['REQUEST_URI'];
    }

    // Des méthodes supplémentaires peuvent être ajoutées pour gérer d'autres aspects de la requête,
    // comme les en-têtes, les cookies, les fichiers, etc.
}
```

### README for Request.php

**Overview:**
This documentation centers on the `Request.php` file, which defines the `Request` class responsible for abstracting and accessing various parts of the HTTP request in a secure and convenient manner.

**Table of Contents:**
- **GET and POST Data Retrieval**
- **Request Information**

| Section                  | Description                                                                                              |
|--------------------------|----------------------------------------------------------------------------------------------------------|
| **GET and POST Data Retrieval** | Describes methods for safely retrieving GET and POST data, allowing default values if specific keys are not found. |
| **Request Information**         | Outlines methods for accessing essential request details like the HTTP method and the request URI.       |

### File and Relationship Tracking for Request.php

- **File:** `Request.php`
  - **Purpose:** Facilitates the extraction and management of request data, such as GET and POST parameters, and provides access to fundamental request properties like method and URI.
  - **Relationships:**
    - **Router.php:** The `Router` class uses the `Request` class to determine the current request's URI and HTTP method to route requests appropriately.
    - **Controller Classes:** Controllers throughout the application rely on the `Request` class to access user input (GET/POST data) and other request specifics, ensuring that request handling is consistent and centralized.
    - **Middleware Classes:** If the application employs middleware, these components may use the `Request` class to preprocess request data, validate inputs, or manage session data before forwarding the request to the appropriate controller.

This comprehensive view and code enhancement aim to bolster the understanding of the `Request` class's role within the application's architecture, emphasizing its critical function in managing and processing HTTP requests.