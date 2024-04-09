<?php
// public\index.php

require_once __DIR__ . '/../vendor/autoload.php';

// - Entry point of the application. Include error handling and request validation.
$app = new \App\Core\Application();

// Define routes
$app->router->get('/', 'HomeController@index');
$app->router->get('/register', 'UserController@showRegistrationForm');
$app->router->post('/register', 'UserController@register');

// - Define routes and their corresponding handlers or controllers.
$app->run();
// - Execute the application, handling the request and sending the response.
