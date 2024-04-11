<?php
namespace App\Views\User; // Update the namespace declaration

use App\Models\User; // Update the namespace declaration
use App\Core\SessionManager; // Corrected the namespace declaration
use PDOException; // Import PDOException class
use Exception; // Import Exception class
use App\Core\ValidationException; // Import ValidationException class
use App\Core\ErrorHandling; // Import ErrorHandling functions
use App\Core\Logger;


session_start();

// Include error handling functions
include_once __DIR__ . '/error.php';
// Include the Logger class
include_once __DIR__ . '/../../Core/Logger.php';
// Inclusion des fichiers d'en-tête pour la configuration globale ou spécifique à la page
include_once __DIR__ . '/../header.php';


$registrationFeedback = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm-password'] ?? '';
    $username = trim($_POST['username']);
    $name = trim($_POST['name']);
    $surname = trim($_POST['surname']);
    $born = $_POST['born'];
    $country = trim($_POST['country']);

    // Ajoutez ici votre logique de validation des données
    
    // Vérification de la correspondance des mots de passe et autres validations...
    if ($password !== $confirmPassword) {
        $registrationFeedback = "Les mots de passe ne correspondent pas.";
    } else {
        try {
            // Insertion de l'utilisateur dans la base de données
            // Exemple fictif d'appel à une méthode create de l'objet User
            $result = User::create([
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT), // Toujours hasher les mots de passe
                'username' => $username,
                'name' => $name,
                'surname' => $surname,
                'born' => $born,
                'country' => $country, // Assurez-vous que cela correspond à la structure de votre base de données
                // 'role', 'status' seront définis dans la logique de votre application
                
            ]);

            // Validation de l'email
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $registrationFeedback = "L'adresse e-mail n'est pas valide.";
            }
            // Validation du mot de passe
            elseif (strlen($password) < 8) {
                $registrationFeedback = "Le mot de passe doit contenir au moins 8 caractères.";
            }
            // Validation de la correspondance des mots de passe
            elseif ($password !== $confirmPassword) {
                $registrationFeedback = "Les mots de passe ne correspondent pas.";
            }
            // Validation du nom d'utilisateur
            elseif (strlen($username) < 3) {
                $registrationFeedback = "Le nom d'utilisateur doit contenir au moins 3 caractères.";
            }
            // Vérification si le nom d'utilisateur contient des caractères non autorisés
            elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
                $registrationFeedback = "Le nom d'utilisateur ne peut contenir que des lettres, des chiffres et des underscores.";
            }
            // Validation du prénom
            elseif (strlen($name) > 30) {
                $registrationFeedback = "Le prénom ne peut pas dépasser 30 caractères.";
            }
            // Validation du nom de famille
            elseif (strlen($surname) > 30) {
                $registrationFeedback = "Le nom de famille ne peut pas dépasser 30 caractères.";
            }
            // Validation de la date de naissance
            elseif (!strtotime($born)) {
                $registrationFeedback = "La date de naissance n'est pas valide.";
            }
            // Vérification si la date de naissance est dans le passé
            elseif (strtotime($born) > time()) {
                $registrationFeedback = "La date de naissance ne peut être dans le futur.";
            }
            // Validation du pays
            elseif (!in_array($country, ['FR', 'DE', 'US', 'CA'])) {
                $registrationFeedback = "Pays non valide. Veuillez choisir parmi les options disponibles.";
            }

            
            if ($result) {
                $registrationFeedback = "Inscription réussie. Vous pouvez maintenant vous connecter.";
            } else {
                $registrationFeedback = "Un problème est survenu lors de l'inscription.";
            }
        } catch (PDOException $e) {
            $registrationFeedback = "Erreur de base de données lors de l'inscription : " . $e->getMessage();
            // Log the error using the Logger class
            $logger = new Logger();
            $logger->error($registrationFeedback);
        } catch (ValidationException $e) {
            $registrationFeedback = "Erreur de validation lors de l'inscription : " . $e->getMessage();
            // Log the error using the Logger class
            $logger = new Logger();
            $logger->error($registrationFeedback);
        } catch (Exception $e) {
            $registrationFeedback = "Erreur lors de l'inscription : " . $e->getMessage();
            // Log the error using the Logger class
            $logger = new Logger();
            $logger->error($registrationFeedback);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="\public\js\fetch_countries.js" defer></script>
    <script src="../../../public/js/fetch_countries.js" defer></script>
</head>
<body>
<main class="mt-10">
    <section class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden md:max-w-lg">
        <header class="bg-blue-500 text-white p-4">
            <h1 class="text-xl font-bold mb-2">Inscription</h1>
        </header>
        
        <form method="post" action="" class="p-5">
            <div class="mb-4">
                <label for="email" class="font-bold mb-2">Adresse e-mail :</label>
                <input type="email" id="email" name="email" required class="w-full px-3 py-2 border rounded">
            </div>
            <div class="mb-4">
                <label for="username" class="font-bold mb-2">Nom d'utilisateur :</label>
                <input type="text" id="username" name="username" required class="w-full px-3 py-2 border rounded">
            </div>
            <div class="mb-4">
                <label for="password" class="font-bold mb-2">Mot de passe :</label>
                <input type="password" id="password" name="password" required class="w-full px-3 py-2 border rounded">
            </div>
            <div class="mb-4">
                <label for="confirm-password" class="font-bold mb-2">Confirmez le mot de passe :</label>
                <input type="password" id="confirm-password" name="confirm-password" required class="w-full px-3 py-2 border rounded">
            </div>
            <div class="mb-4">
                <label for="name" class="font-bold mb-2">Prénom :</label>
                <input type="text" id="name" name="name" required class="w-full px-3 py-2 border rounded">
            </div>
            <div class="mb-4">
                <label for="surname" class="font-bold mb-2">Nom de famille :</label>
                <input type="text" id="surname" name="surname" required class="w-full px-3 py-2 border rounded">
            </div>
            <div class="mb-4">
                <label for="born" class="font-bold mb-2">Date de naissance :</label>
                <input type="date" id="born" name="born" required class="w-full px-3 py-2 border rounded">
            </div>


            <div class="mb-4">
                <label for="country" class="font-bold mb-2">Pays :</label>
                <select id="country" name="country" required class="w-full px-3 py-2 border rounded">
                    <!-- Options will be populated dynamically by JavaScript -->
                </select>
            </div>



            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">S'inscrire</button>
                <div class="mt-3 text-red-500"><?= htmlspecialchars($registrationFeedback) ?></div>
            </form>
        </section>
    </main>
</body>
</html>

<?php include_once __DIR__ . '/../footer.php'; ?>
