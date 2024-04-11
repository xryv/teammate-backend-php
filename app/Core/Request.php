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
