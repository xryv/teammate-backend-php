<?php 
// app\Core\Router.php
namespace Teammate\Core;

class Router {
    protected $routes = [];

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
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = $this->normalizeUri(trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/'));

        if (isset($this->routes[$method][$uri])) {
            $action = $this->routes[$method][$uri];
            if (is_callable($action)) {
                call_user_func($action);
            } else {
                // If the action is not callable, then respond with a 404 error
                $this->respondNotFound();
            }
        } else {
            $this->respondNotFound();
        }
    }
    
    protected function normalizeUri($uri) {
        return '/' . trim($uri, '/');
    }

    protected function respondNotFound($message = "404 Not Found") {
        header("HTTP/1.0 404 Not Found");
        echo $message;
    }
}
