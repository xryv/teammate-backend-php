<?php
// public/index.php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Auth\Authenticator;
use App\Models\User;

session_start();

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
    // Example of simple validation and sanitization
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? ''; // Password hashing nullifies the need for sanitization
    $username = filter_var($_POST['username'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Proceed only if validation passes
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($password) && !empty($username)) {
        $result = User::create([
            'email' => $email,
            'password' => $password,
            // Include other fields as needed, ensuring they are validated and sanitized
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


// Dashboard
$router->get('/dashboard', function() {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: /login");
        exit;
    }
    
    include __DIR__ . '/../app/Views/admin/dashboard.php';
});

// Implement login and logout similarly using Authenticator
// Display the login form
$router->get('/login', function() {
    include __DIR__ . '/../app/Views/user/login.php';
});

// Process the login form submission
$router->post('/login', function() {
    // Assume $authenticator is an instance of your Authenticator class
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? ''; // Directly using this in password_verify so no need for sanitization

    if (Authenticator::login($email, $password)) {
        // Login successful, redirect to dashboard or home
        header("Location: /dashboard");
        exit;
    } else {
        // Login failed, show an error message or redirect back to the login form
        echo "Login failed. Please check your credentials and try again.";
    }
});

// Process logout
$router->get('/logout', function() {
    Authenticator::logout();
    // Redirect to the home page or login page after logging out
    header("Location: /login");
    exit;
});



$router->dispatch(); // Process the current request
