<?php
declare(strict_types=1);

namespace App\Core; // Update the namespace declaration

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Core/error_handling.php';

use App\Core\Router;
use App\Auth\Authenticator;
use App\Models\User;

session_start();
if (!isset($_SESSION['initiated'])) {
    session_regenerate_id(true);
    $_SESSION['initiated'] = true;
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$router = new Router();

$router->get('/', function() {
    view('welcome', [
        'title' => 'Welcome to Teammate App',
        'images' => [
            'login' => 'https://source.unsplash.com/random/400x300?sig=login',
            'logout' => 'https://source.unsplash.com/random/400x300?sig=logout',
            'profile' => 'https://source.unsplash.com/random/400x300?sig=profile',
            'register' => 'https://source.unsplash.com/random/400x300?sig=register',
            'dashboard' => 'https://source.unsplash.com/random/400x300?sig=dashboard',
        ],
    ]);
});

$router->get('/register', fn() => view('user/register'));
$router->post('/register', function() {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('CSRF token mismatch.');
    }

    $data = sanitizeInput($_POST);
    if (validateRegistration($data)) {
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        if (User::create($data)) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            redirect('/login');
        } else {
            echo "Registration failed. Try again.";
        }
    } else {
        echo "Invalid input data provided.";
    }
});

$router->get('/login', fn() => view('user/login'));
$router->post('/login', function() {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('CSRF token mismatch.');
    }

    $data = sanitizeInput($_POST);
    $user = User::findByEmail($data['email']);
    if ($user && password_verify($data['password'], $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        redirect('/dashboard');
    } else {
        echo "Login failed. Please check your credentials and try again.";
    }
});

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

$router->dispatch();

function view(string $path, array $data = []) {
    extract($data);
    require __DIR__ . "/../app/Views/{$path}.php";
}

function redirect(string $path) {
    header("Location: $path");
    exit;
}

function sanitizeInput(array $input) {
    return array_map('trim', [
        'email' => filter_var($input['email'] ?? '', FILTER_SANITIZE_EMAIL),
        'password' => $input['password'] ?? '',
        'username' => filter_var($input['username'] ?? '', FILTER_SANITIZE_FULL_SPECIAL_CHARS),
    ]);
}

function validateRegistration(array $data): bool {
    return filter_var($data['email'], FILTER_VALIDATE_EMAIL) &&
           !empty($data['password']) &&
           !empty($data['username']) &&
           strlen($data['password']) >= 8; // Example validation rule
}

function isAuthenticated(): bool {
    return isset($_SESSION['user_id']);
}

?>
<?php include_once 'header.php'; ?>

<div class="container mx-auto px-4">
    <h1 class="text-4xl font-bold text-center my-10"><?= $title ?></h1>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($images as $key => $url): ?>
            <div class="max-w-sm rounded overflow-hidden shadow-lg">
                <img class="w-full" src="<?= $url ?>" alt="<?= $key ?>">
                <div class="px-6 py-4">
                    <div class="font-bold text-xl mb-2"><?= ucfirst($key) ?></div>
                    <p class="text-gray-700 text-base">
                        Explore <?= $key ?> functionality in our application.
                    </p>
                </div>
                <div class="px-6 pt-4 pb-2">
                    <a href="/<?= $key ?>" class="inline-block bg-blue-500 rounded-full px-3 py-1 text-sm font-semibold text-white mr-2 mb-2">Go to <?= ucfirst($key) ?></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include_once 'footer.php'; ?>
