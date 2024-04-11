<?php
// app/Auth/Authenticator.php
namespace App\Auth;

use App\Core\Database; // Correction du chemin d'accès à la classe Database
use PDO;
use Exception;

class Authenticator {
    /**
     * Tente de connecter un utilisateur.
     * 
     * @param string $email L'email de l'utilisateur.
     * @param string $password Le mot de passe de l'utilisateur.
     * @return bool Retourne true si la connexion réussit, sinon false.
     */
    public static function login($email, $password): bool {
        try {
            // Assurez-vous que la session est démarrée
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            $pdo = Database::getInstance();

            // Préparation et exécution de la requête
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user || !password_verify($password, $user['password'])) {
                // Utilisateur non trouvé ou mot de passe incorrect
                return false;
            }

            // Connexion réussie, définir les variables de session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role']; // Supposons que vous avez une colonne de rôle

            return true;
        } catch (Exception $e) {
            // Log de l'erreur pour le suivi
            error_log("Erreur de connexion: " . $e->getMessage(), 3, __DIR__ . '/../../logs/error_log.log');
            throw new Exception("Un problème est survenu lors de la tentative de connexion.");
        }
    }

    /**
     * Déconnecte l'utilisateur en détruisant la session.
     */
    public static function logout() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        session_unset(); // Supprimer toutes les variables de session
        session_destroy(); // Détruire la session
    }
}
