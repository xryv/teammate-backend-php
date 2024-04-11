<?php 
// teammate\app\Views\user\error.php
namespace App\Core;

use App\Core\Logger;

?>

<!-- app/Views/error.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error - Application</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <div class="bg-white rounded-lg shadow-xl p-6 mt-12">
            <h1 class="text-2xl font-bold text-red-500 mb-4">An error occurred</h1>
            <p class="mb-4">We're sorry, but something went wrong. We're working to get it fixed as soon as we can.</p>
            <?php if (getenv('APP_ENV') === 'development'): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <strong class="font-bold">Error Details:</strong>
                    <span class="block sm:inline">
                        <?php
                        // Assuming Logger.php has a method to fetch logged errors.
                        // This is hypothetical and you'll need to implement it according to your Logger's capabilities.
                        $errors = Logger::getLoggedErrors(); // Fetches errors logged in the system
                        if (!empty($errors)) {
                            echo '<ul>';
                            foreach ($errors as $error) {
                                echo "<li>{$error}</li>";
                            }
                            echo '</ul>';
                        } else {
                            echo '<p>No error details available.</p>';
                        }
                        ?>
                    </span>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
