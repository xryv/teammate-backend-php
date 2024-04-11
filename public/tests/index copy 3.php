<?php
declare(strict_types=1);

// public/index.php
// require_once __DIR__ . '/../app/Core/Router.php';
// require_once __DIR__ . '/../app/Auth/Authenticator.php';
// require_once __DIR__ . '/../app/Models/User.php';


require_once __DIR__ . '/../vendor/autoload.php';

// Global Exception Handler
set_exception_handler(function ($exception) {
    error_log($exception->getMessage()); // Log the exception
    require_once __DIR__ . '/../app/Views/error.php'; // Display a generic error page
});

// Global Error Handler
set_error_handler(function ($severity, $message, $file, $line) {
    throw new ErrorException($message, 0, $severity, $file, $line);
});

// Shutdown Function for Fatal Errors
register_shutdown_function(function () {
    $error = error_get_last();
    if ($error !== null) {
        error_log($error['message']); // Log the error
        require_once __DIR__ . '/../app/Views/error.php'; // Display a generic error page
    }
});

// use App\Core\Router;
// use App\Auth\Authenticator;
// use App\Models\User;
use Teammate\Core\Router;
use Teammate\Auth\Authenticator;
use Teammate\Models\User;



session_start();

// Regenerate session ID to prevent session fixation attacks
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id();
    $_SESSION['initiated'] = true;
}

var_dump(class_exists(Teammate\Core\Router::class));

// $router = new Router();
$router = new Teammate\Core\Router;
// $router = new App\Core\Router;

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
    // Simplified example of validation and sanitization
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? ''; // Direct usage in password hashing
    $username = filter_var($_POST['username'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    // Proceed only if validation passes
    if (filter_var($email, FILTER_VALIDATE_EMAIL) && !empty($password) && !empty($username)) {
        $result = User::create([
            'email' => $email,
            'password' => $password,
            'username' => $username,
            // Assume other necessary fields are included and sanitized
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

// Display the login form
$router->get('/login', function() {
    include __DIR__ . '/../app/Views/user/login.php';
});

// Process the login form submission
$router->post('/login', function() {
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? ''; 

    if (Authenticator::login($email, $password)) {
        // Ensure session security
        $_SESSION['initiated'] = true;
        header("Location: /dashboard");
        exit;
    } else {
        echo "Login failed. Please check your credentials and try again.";
    }
});

// Process logout
$router->get('/logout', function() {
    Authenticator::logout();
    header("Location: /login");
    exit;
});

$router->dispatch(); // Process the current request
