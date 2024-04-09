<?php 
// app\Models\User.php

namespace App\Models;

// - Use namespaces to organize code logically.
// - Depend on the Database class for DB operations, applying Dependency Injection where possible.
class User {
    // - Define properties for each user attribute with proper visibility.
    // - Use constructor for optional dependency injection and initialization.
    // - CRUD methods below follow the principle of single responsibility.
    
    public function create(array $userData) {
        // - Validate $userData to ensure integrity.
        // - Prepare SQL statement to prevent SQL injection.
        // - Execute insertion operation and handle exceptions/errors.
    }

    public function read(int $userId = null) {
        // - If $userId is null, fetch all users, else fetch specific user by ID.
        // - Use prepared statements to mitigate SQL injection risks.
        // - Return user data or an array of users.
    }

    public function update(int $userId, array $newUserData) {
        // - Validate new user data.
        // - Prepare SQL statement for update operation.
        // - Bind parameters and execute, handle exceptions as needed.
    }

    public function delete(int $userId) {
        // - Ensure proper user ID validation.
        // - Use prepared statements for deletion.
        // - Execute and handle possible exceptions.
    }
}