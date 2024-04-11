<?php
namespace App\Views\user; // Update the namespace declaration

use App\Auth\Authenticator; // Update the namespace for Authenticator
// use App\Views\admin\dashboard;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If user is already logged in, redirect to the dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: /dashboard.php");
    exit;
}

$loginFeedback = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die('CSRF token mismatch.');
    }
    
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';

    if (!empty($email) && !empty($password)) {
        if (Authenticator::login($email, $password)) {
            // Regenerate token for next form
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
            header("Location: /profile");
            exit;
        } else {
            $loginFeedback = "Connexion échouée. Veuillez vérifier vos identifiants et essayer à nouveau.";
        }
    } else {
        $loginFeedback = "Veuillez fournir à la fois un email et un mot de passe.";
    }
}

include_once __DIR__ . '/../header.php';
?>

<!-- Intégration du CDN Tailwind CSS pour le style -->
<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

<main class="main-content mt-10">
    <section id="login" class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden md:max-w-lg">
        <header class="card-header bg-blue-500 text-white p-4">
            <h1 class="text-xl font-bold mb-2">Connexion</h1>
        </header>
        
        <form id="login-form" method="post" action="" class="p-5">
            <div class="form-group mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Adresse e-mail :</label>
                <input type="email" id="email" name="email" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="test@example.com">
            </div>
            <div class="form-group mb-6">
                <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Mot de passe :</label>
                <input type="password" id="password" name="password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" placeholder="●●●●●●●">
            </div>
            <div class="form-group mb-6">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Se connecter</button>
            </div>
            <div id="loginFeedback" class="text-red-500 text-xs italic"><?= htmlspecialchars($loginFeedback) ?></div>
        </form>
        
        <div class="px-5 py-4">
            <p class="text-sm">Pas encore de compte ? <a href="register.php" class="text-blue-500 hover:text-blue-800">Inscrivez-vous</a></p>
        </div>
    </section>
</main>

<?php
include_once __DIR__ . '/../footer.php';
?>
