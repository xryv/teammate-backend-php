<?php 
// app\Core\Router.php

// namespace App\Core;
namespace Teammate\Core;

class Router {
    protected $routes = [];
    protected $currentRoute = null;

    public function get($uri, $action) {
        $this->addRoute('GET', $uri, $action);
    }

    public function post($uri, $action) {
        $this->addRoute('POST', $uri, $action);
    }

    protected function addRoute($method, $uri, $action) {
        $this->routes[$method][$uri] = $action;
    }

    public function dispatch() {
        // Derive the request method and URI from the server's global variables
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
        
        // Normalize the URI to ensure routing consistency
        $uri = $this->normalizeUri($uri);
        
        // Match the request to the defined routes
        if (isset($this->routes[$method][$uri])) {
            $action = $this->routes[$method][$uri];
            // If action is a closure, execute it
            if (is_callable($action)) {
                call_user_func($action);
            } else {
                // Assume the action is a string in 'Controller@method' format
                list($controller, $method) = explode('@', $action, 2);
                $this->callAction($controller, $method);
            }
        } else {
            // Handle 404 Not Found
            $this->respondNotFound();
        }
    }
    
    protected function normalizeUri($uri) {
        // Remove leading and trailing slashes
        return '/' . trim($uri, '/');
    }
    
    protected function callAction($controller, $method) {
        // Assuming controllers live in the App\Controllers namespace and suffix with "Controller"
        $controller = "Teammate\\Controllers\\" . $controller . "Controller";
        if (!method_exists($controller, $method)) {
            $this->respondNotFound("Controller or method not found.");
            return;
        }
        
        $controllerInstance = new $controller();
        $controllerInstance->$method();
    }
    
    protected function respondNotFound($message = "404 Not Found") {
        header("HTTP/1.0 404 Not Found");
        echo $message;
    }
    

    // This function is reserved for future use with actual controller classes
    /*
    protected function callAction($controller, $method) {
        // Instantiate controller and call method
        $controller = "App\\Controllers\\{$controller}";
        (new $controller)->$method();
    }
    */
}
