<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Core/error_handling.php';

use Teammate\Core\Router;
use Teammate\Auth\Authenticator;
use Teammate\Models\User;

session_start();
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id();
    $_SESSION['initiated'] = true;
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$router = new Router();

$router->get('/', function() {
    $images = [
        'login' => 'https://source.unsplash.com/random/400x300?sig=login',
        'logout' => 'https://source.unsplash.com/random/400x300?sig=logout',
        'profile' => 'https://source.unsplash.com/random/400x300?sig=profile',
        'register' => 'https://source.unsplash.com/random/400x300?sig=register',
        'dashboard' => 'https://source.unsplash.com/random/400x300?sig=dashboard'
    ];
    view('welcome', compact('images'));
});

$router->get('/register', fn() => view('user/register'));
$router->get('/login', fn() => view('user/login'));
$router->get('/logout', function() {
    Authenticator::logout();
    redirect('/login');
});
$router->get('/profile', function() {
    isAuthenticated() ? view('user/profile') : redirect('/login');
});
$router->get('/dashboard', function() {
    isAuthenticated() ? view('admin/dashboard') : redirect('/login');
});

$router->post('/register', function() {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('CSRF token mismatch.');
    }

    $data = sanitizeInput($_POST);
    if (validateRegistration($data)) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT); // Hash password
        if (User::create($data)) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Regenerate CSRF token
            // Optionally, set user session or redirect with a success message
            redirect('/login');
        } else {
            echo "Registration failed. Try again.";
        }
    } else {
        echo "Invalid input data provided.";
    }
});


$router->post('/login', function() {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('CSRF token mismatch.');
    }

    $data = sanitizeInput($_POST);
    $user = User::findByEmail($data['email']); // Assuming you have this method in your User model

    if ($user && password_verify($data['password'], $user['password'])) {
        // Assuming you're storing user ID in the session for logged in user
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Regenerate CSRF token
        // Optionally, set other user session variables or redirect with a success message
        redirect('/dashboard');
    } else {
        echo "Login failed. Please check your credentials and try again.";
    }
});


$router->dispatch();

function view(string $path, array $data = []) {
    extract($data);
    require __DIR__ . "/../app/Views/{$path}.php";
}

function redirect(string $path) {
    header("Location: $path");
    exit;
}

function isAuthenticated(): bool {
    return isset($_SESSION['user_id']);
}
