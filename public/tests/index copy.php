<?php
// public\index.php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Application;
use App\Core\Router;

// - Entry point of the application. Include error handling and request validation.
$app = new Application();
$router = new Router();

// Define routes
$app->router->get('/', 'HomeController@index');
$app->router->get('/register', 'UserController@showRegistrationForm');
$app->router->post('/register', 'UserController@register');

$router->get('/', 'DashboardController@index'); // Assuming you have a DashboardController for the admin dashboard

$router->get('/login', 'Authenticator@showLoginForm');
$router->post('/login', 'Authenticator@login');
$router->get('/logout', 'Authenticator@logout');

// - Define routes and their corresponding handlers or controllers.
// $app->run();
// Run the application
$app->run($router);
// - Execute the application, handling the request and sending the response.
