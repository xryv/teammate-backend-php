<?php
declare(strict_types=1);

require_once __DIR__ . '/../vendor/autoload.php';

use Teammate\Core\Router;
use Teammate\Auth\Authenticator;
use Teammate\Models\User;

session_start();

// Regenerate session ID to prevent session fixation attacks
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id();
    $_SESSION['initiated'] = true;
}

// Set up global exception and error handling
set_exception_handler(function ($exception) {
    error_log($exception->getMessage());
    require_once __DIR__ . '/../app/Views/error.php';
});

set_error_handler(function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

register_shutdown_function(function () {
    $error = error_get_last();
    if ($error !== null) {
        error_log($error['message']);
        require_once __DIR__ . '/../app/Views/error.php';
    }
});

$router = new Router();

// Home Route
$router->get('/', function() {
    include __DIR__ . '/../app/Views/header.php';
    echo 'Welcome to the Teammate App!';
    include __DIR__ . '/../app/Views/footer.php';
});

// Registration Page Display
$router->get('/register', function() {
    include __DIR__ . '/../app/Views/user/register.php';
});

// Handling Registration Data
$router->post('/register', function() {
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $username = filter_var($_POST['username'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($password) && !empty($username)) {
        $result = User::create([
            'email' => $email,
            'password' => $password,
            'username' => $username,
        ]);

        if ($result) {
            header("Location: /dashboard");
            exit;
        } else {
            echo "Registration failed. Try again.";
        }
    } else {
        echo "Invalid input data provided.";
    }
});

// Dashboard Access
$router->get('/dashboard', function() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: /login");
        exit;
    }
    
    include __DIR__ . '/../app/Views/admin/dashboard.php';
});

// Login Form Display
$router->get('/login', function() {
    include __DIR__ . '/../app/Views/user/login.php';
});

// Process Login Form Submission
$router->post('/login', function() {
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? ''; 

    if (Authenticator::login($email, $password)) {
        $_SESSION['initiated'] = true;
        header("Location: /dashboard");
        exit;
    } else {
        echo "Login failed. Please check your credentials and try again.";
    }
});

// Process Logout
$router->get('/logout', function() {
    Authenticator::logout();
    header("Location: /login");
    exit;
});

$router->dispatch();
