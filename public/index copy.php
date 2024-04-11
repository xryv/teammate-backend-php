<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Core/error_handling.php';

use Teammate\Core\Router;
use Teammate\Auth\Authenticator;
use Teammate\Models\User;

// Initialize the session and set up a secure session environment
session_start();
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id();
    $_SESSION['initiated'] = true;
}


// Create a new router instance
$router = new Router();

// Define routes for the application
$router->get('/', fn() => view('welcome'));

$router->get('/register', fn() => view('user/register'));

$router->post('/register', function() {
    $data = sanitizeInput($_POST);
    if (validateRegistration($data)) {
        if (User::create($data)) {
            redirect('/dashboard');
        } else {
            echo "Registration failed. Try again.";
        }
    } else {
        echo "Invalid input data provided.";
    }
});

$router->get('/login', fn() => view('user/login'));

$router->post('/login', function() {
    $data = sanitizeInput($_POST);
    if (Authenticator::login($data['email'], $data['password'])) {
        secureSession();
        redirect('/dashboard');
    } else {
        echo "Login failed. Please check your credentials and try again.";
    }
});

$router->get('/dashboard', function() {
    isAuthenticated() ? view('admin/dashboard') : redirect('/login');
});

$router->get('/logout', function() {
    Authenticator::logout();
    redirect('/login');
});

// Dispatch the request to the appropriate route
$router->dispatch();

// Helper functions for cleaner code
function view(string $path, array $data = []) {
    extract($data);
    require __DIR__ . "/../app/Views/{$path}.php";
}

function redirect(string $path) {
    header("Location: $path");
    exit;
}

function sanitizeInput(array $input) {
    return [
        'email' => filter_var($input['email'] ?? '', FILTER_SANITIZE_EMAIL),
        'password' => $input['password'] ?? '',
        'username' => filter_var($input['username'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
        // Sanitize other fields as necessary
    ];
}

function validateRegistration(array $data): bool {
    // Implement your validation logic here
    return filter_var($data['email'], FILTER_VALIDATE_EMAIL) &&
           !empty($data['password']) &&
           !empty($data['username']);
}

function secureSession() {
    $_SESSION['initiated'] = true;
}

function isAuthenticated(): bool {
    return isset($_SESSION['user_id']);
}
