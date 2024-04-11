### Enhanced Code for User.php with Pseudocode and French Comments

```php
<?php
// app\Models\User.php
namespace App\Models;

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
    public function __construct() {
        // Constructeur pour injection de dépendances si nécessaire à l'avenir
    }

    // Crée un nouvel enregistrement utilisateur dans la base de données
    public static function create(array $userData): bool {
        try {
            $pdo = Database::getInstance();
            // ... code pour créer un utilisateur ...
        } catch (PDOException $e) {
            // ... gestion des exceptions ...
        }
    }

    // Lire les informations d'un utilisateur ou de tous les utilisateurs
    public static function read(int $userId = null): array {
        $pdo = Database::getInstance();
        // ... code pour lire les informations ...
    }

    // Met à jour un enregistrement utilisateur dans la base de données
    public static function update(int $userId, array $newUserData): bool {
        try {
            $pdo = Database::getInstance();
            // ... code pour mettre à jour un utilisateur ...
        } catch (PDOException $e) {
            // ... gestion des exceptions ...
        }
    }

    // Supprime un utilisateur de la base de données
    public static function delete(int $userId): bool {
        try {
            $pdo = Database::getInstance();
            // ... code pour supprimer un utilisateur ...
        } catch (PDOException $e) {
            // ... gestion des exceptions ...
        }
    }

    // Récupère tous les utilisateurs de la base de données
    public static function findAll(): array {
        $pdo = Database::getInstance();
        // ... code pour trouver tous les utilisateurs ...
    }

    // Récupère un utilisateur par son ID
    public static function getUserById(int $userId): ?array {
        $pdo = Database::getInstance();
        // ... code pour récupérer un utilisateur par ID ...
    }

    // Trouve un utilisateur par son e-mail
    public static function findByEmail(string $email): ?array {
        $pdo = Database::getInstance();
        // ... code pour trouver un utilisateur par e-mail ...
    }
}
```

### README for User.php

**Overview:**
This README summarizes the functionalities and use cases of the `User.php` model, which is responsible for managing user-related operations in the database.

**Table of Contents:**
- **User Creation**
- **User Information Retrieval**
- **User Update**
- **User Deletion**
- **Finding Users**
- **User Search by ID and Email**

| Function               | Description                                                                                     | Return Type  |
|------------------------|-------------------------------------------------------------------------------------------------|--------------|
| `create`               | Creates a new user record with hashed password and additional provided data.                    | `bool`       |
| `read`                 | Fetches single or multiple user records from the database.                                      | `array`      |
| `update`               | Updates a user record with new data based on user ID.                                           | `bool`       |
| `delete`               | Removes a user record from the database based on user ID.                                       | `bool`       |
| `findAll`              | Retrieves all user records from the database.                                                   | `array`      |
| `getUserById`          | Retrieves user data for a specific user ID.                                                     | `array|null` |
| `findByEmail`          | Finds a user by their email and retrieves their data.                                           | `array|null` |

**Use Cases:**
- Registering new users
- Authenticating users
- Displaying user profiles
- Updating user accounts
- Deleting users from the system
- Administering users in a backend system

**Relationships:**
- Directly interacts with the `Database` class.
- Can be used by controllers to facilitate user-related features.

**Improvements and Recommendations:**
- **Security:** Ensure password hashing and proper data sanitization.
- **Validation:** Add server-side validation for all user input.
- **Error Handling:** Improve exception handling and error messaging.
- **Functionality:** Extend with additional methods for password reset, email verification, etc.
- **Optimization:** Optimize queries for performance, especially when scaling to a large number of users.

**How to Use:**
Instantiate the `User` model and call the relevant methods to perform CRUD operations on user data.

**How to Remove:**
If you need to remove

 the `User` model, ensure that no other part of your application relies on it. Update or remove any controllers, views, or other models that interact with the `User` model.