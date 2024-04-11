<?php
// app\Models\User.php
namespace App\Models;

// use App\Core\Config;
use App\Core\Database;
use PDO;
use PDOException;

/*
Pseudocode:
Fonctions pour interagir avec la table 'User' dans la base de données.
- Créer un nouvel utilisateur
- Lire les informations d'un utilisateur ou de tous les utilisateurs
- Mettre à jour les informations d'un utilisateur
- Supprimer un utilisateur
- Trouver tous les utilisateurs
- Récupérer un utilisateur par son ID
- Trouver un utilisateur par son e-mail
*/

class User {
    // Constructor for dependency injection (if needed in the future)
    public function __construct() {}

    /**
     * Create a new user record in the database.
     * 
     * @param array $userData Array of user data.
     * @return bool Returns true on success, false on failure.
     */
    public static function create(array $userData): bool {
        try {
            $pdo = Database::getInstance();

            // Prepare SQL statement to prevent SQL injection.
            $stmt = $pdo->prepare("INSERT INTO User (email, password, username, name, surname, born, id_role, id_status, id_country) VALUES (:email, :password, :username, :name, :surname, :born, :id_role, :id_status, :id_country)");
            
            // Hash password for security.
            $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
            
            // Execute the insertion operation.
            $stmt->execute($userData);

            return true;
        } catch (PDOException $e) {
            // Log error for troubleshooting.
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Read user(s) from the database.
     * If $userId is provided, fetches a single user; otherwise, fetches all users.
     * 
     * @param int|null $userId ID of the user to fetch.
     * @return array User data.
     */
    public static function read(int $userId = null): array {
        $pdo = Database::getInstance();

        if ($userId === null) {
            // Fetch all users.
            $stmt = $pdo->query("SELECT * FROM User");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            // Fetch a specific user by ID using a prepared statement.
            $stmt = $pdo->prepare("SELECT * FROM User WHERE id_user = :id");
            $stmt->execute([':id' => $userId]);
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        }
    }

    /**
     * Update a user record in the database.
     * 
     * @param int $userId The ID of the user to update.
     * @param array $newUserData New data for the user.
     * @return bool Returns true on success, false on failure.
     */
    public static function update(int $userId, array $newUserData): bool {
        try {
            $pdo = Database::getInstance();

            // Prepare SQL statement for update operation.
            $stmt = $pdo->prepare("UPDATE User SET email = :email, username = :username, name = :name, surname = :surname, born = :born, id_role = :id_role, id_status = :id_status, id_country = :id_country WHERE id_user = :id_user");
            
            // Include userID in the newUserData array for parameter binding.
            $newUserData['id_user'] = $userId;

            $stmt->execute($newUserData);

            return true;
        } catch (PDOException $e) {
            // Log error for troubleshooting.
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Delete a user from the database.
     * 
     * @param int $userId The ID of the user to delete.
     * @return bool Returns true on success, false on failure.
     */
    public static function delete(int $userId): bool {
        try {
            $pdo = Database::getInstance();

            // Use prepared statements for deletion to prevent SQL injection.
            $stmt = $pdo->prepare("DELETE FROM User WHERE id_user = :id");
            $stmt->execute([':id' => $userId]);

            return true;
        } catch (PDOException $e) {
            // Log error for troubleshooting.
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Find all users in the database.
     * 
     * @return array List of all users.
     */
    public static function findAll(): array {
        $pdo = Database::getInstance();
        $stmt = $pdo->query("SELECT * FROM User");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



  /**
     * Récupère un utilisateur par son ID.
     * 
     * @param int $userId ID de l'utilisateur à récupérer.
     * @return array Données de l'utilisateur.
     */
    public static function getUserById(int $userId): ?array {
        $pdo = Database::getInstance();

        $stmt = $pdo->prepare("SELECT id, role FROM User WHERE id = :id");
        $stmt->execute([':id' => $userId]);
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ? $user : null;
    }

        /**
     * Find a user by their email.
     * 
     * @param string $email Email of the user to find.
     * @return array|null User data or null if not found.
     */
    public static function findByEmail(string $email): ?array {
        $pdo = Database::getInstance();
        
        $stmt = $pdo->prepare("SELECT * FROM User WHERE email = :email");
        $stmt->execute([':email' => $email]);
        
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $user ?: null;
    }


}