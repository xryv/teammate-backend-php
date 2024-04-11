<?php
// app/Core/Router.php

// Mise à jour de la déclaration de l'espace de noms pour refléter la structure des dossiers
namespace App\Core;

/*
Pseudocode:
Définir les routes GET et POST avec leurs actions associées.
Distribuer la requête vers l'action correspondante basée sur l'URI et la méthode HTTP.
Si l'action est une fonction, l'exécuter avec les paramètres de la route.
Si l'action est une chaîne, résoudre vers un contrôleur@methode.
Si aucune route correspondante n'est trouvée, répondre avec 404 Not Found.
*/

// Définition de la classe Router pour gérer le routage des requêtes HTTP
class Router {
    // Tableaux pour stocker les routes et les paramètres des routes
    protected $routes = [];
    protected $params = [];

    // Ajoute une route GET
    public function get($uri, $action) {
        $this->addRoute('GET', $uri, $action);
    }

    // Ajoute une route POST
    public function post($uri, $action) {
        $this->addRoute('POST', $uri, $action);
    }

    // Fonction protégée pour ajouter une route
    protected function addRoute($method, $uri, $action) {
        // Formatage de l'URI pour le matching de route
        $uri = preg_replace('/\//', '\\/', $uri);
        $uri = preg_replace('/\{([a-z]+)\}/', '(?P<\1>[a-z0-9-]+)', $uri);
        $uri = '/^' . $uri . '$/i';
        // Stockage de l'action pour la méthode et l'URI données
        $this->routes[$method][$uri] = $action;
    }

    // Distribue la requête vers l'action appropriée
    public function dispatch() {
        $method = $_SERVER['REQUEST_METHOD']; // Récupère la méthode HTTP
        $uri = $this->normalizeUri(trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/')); // Normalise l'URI

        // Itération sur les routes pour trouver une correspondance
        foreach ($this->routes[$method] as $route => $action) {
            if (preg_match($route, $uri, $matches)) {
                // Extraction des paramètres de l'URI
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $this->params[$key] = $match;
                    }
                }
                // Exécution de l'action associée à la route
                if (is_callable($action)) {
                    call_user_func_array($action, $this->params);
                } else {
                    $this->resolveAction($action);
                }
                return;
            }
        }
        // Réponse 404 si aucune route n'est trouvée
        $this->respondNotFound();
    }

    // Résout l'action sous forme de contrôleur@méthode
    protected function resolveAction($action) {
        if (strpos($action, '@') !== false) {
            list($controller, $method) = explode('@', $action, 2);
            $controller = "App\\Controllers\\$controller"; // Construction du nom complet du contrôleur
            if (class_exists($controller)) {
                $controllerObject = new $controller;
                if (method_exists($controllerObject, $method)) {
                    call_user_func_array([$controllerObject, $method], $this->params);
                    return;
                }
            }
        }
        // Réponse 404 si l'action n'est pas résolue
        $this->respondNotFound("La ressource demandée n'a pas été trouvée.");
    }
    
    // Normalise l'URI pour le matching
    protected function normalizeUri($uri) {
        return '/' . trim($uri, '/');
    }

    // Envoie une réponse 404 Not Found
    protected function respondNotFound($message = "404 Not Found") {
        header("HTTP/1.0 404 Not Found");
        echo $message;
    }
}
