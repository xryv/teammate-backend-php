<?php
// app/Views/header.php
namespace App\Views; // Update the namespace declaration

use App\Models\User;

// Check if a session is active before starting a new one
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include Composer's autoloader
require_once __DIR__ . '/../../vendor/autoload.php';

// Determine the login status and retrieve user information if logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userInfo = null;

if ($isLoggedIn) {
    $userInfo = User::getUserById($_SESSION['user_id']);
}


// Function to logout the user
function logout() {
    // Clear all session data
    $_SESSION = [];

    // Delete the session cookie
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    // Destroy the session
    session_destroy();

    // Redirect to the homepage or login page
    header("Location: /login");
    exit;
}

// Check if the user clicked the logout button
if (isset($_POST['logout'])) {
    logout();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme Teammate</title>
    <!-- CDN de Tailwind CSS pour un style rapide et moderne -->
    <link href="https://cdn.tailwindcss.com" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Ajoutez ici d'autres ressources CSS ou JS si nécessaire -->
</head>
<body>
<header>
    <nav class="bg-gradient-to-r from-blue-500 to-teal-500 p-4 text-white flex justify-between items-center shadow-lg">
        <a href="/Teammate/teammate/public/index.php" class="logo flex items-center">
            <img src="/Teammate/teammate/assets/logo.svg" alt="Logo Teammate" style="height: 40px;">
            <span class="ml-2 text-lg font-bold">Teammate</span>
        </a>

        <div class="flex items-center">
            <?php if ($isLoggedIn && $userInfo): ?>
                <div class="flex items-center mr-4 bg-black bg-opacity-50 py-1 px-3 rounded">
                    <span>ID: <?= htmlspecialchars($userInfo['id']) ?> </span> | 
                    <span>Rôle: <?= htmlspecialchars($userInfo['role']) ?></span>
                </div>
                <a href="/Teammate/teammate/app/Views/user/profile.php" class="bg-blue-300 hover:bg-blue-400 text-white font-bold py-2 px-4 rounded mr-2">Profil</a>
                <form action="/Teammate/teammate/app/Views/user/logout.php" method="POST" class="inline">
                    <button type="submit" name="logout" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        Déconnexion
                    </button>
                </form>
            <?php else: ?>
                <a href="/Teammate/teammate/app/Views/user/login.php" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded mr-2">Connexion</a>
                <a href="/Teammate/teammate/app/Views/user/register.php" class="bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded">Inscription</a>
            <?php endif; ?>
        </div>
    </nav>


</header>
