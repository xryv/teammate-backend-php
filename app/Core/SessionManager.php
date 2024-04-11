<?php // teammate/app/Core/SessionManager.php


/*
Pseudocode:
Démarrer ou reprendre une session.
Déterminer si l'utilisateur est connecté en vérifiant la présence de 'user_id' dans la session.
Si connecté, récupérer les informations de l'utilisateur à partir de son ID.
*/


namespace App\Core; // Correct namespace for autoloading

use App\Models\User; // Assuming User model exists and has a getUserById method

class SessionManager {
    /**
     * Démarre ou reprend une session.
     */
    public function startSession() {
        session_start();
    }

    /**
     * Détermine si l'utilisateur est actuellement connecté.
     * 
     * @return bool Renvoie vrai si l'utilisateur est connecté, faux sinon.
     */
    public function isLoggedIn(): bool {
        return isset($_SESSION['user_id']);
    }

    /**
     * Récupère les informations de l'utilisateur connecté.
     * 
     * @return User|null Les informations de l'utilisateur ou null si non connecté.
     */
    public function getUserInfo(): ?User {
        if ($this->isLoggedIn()) {
            return User::getUserById($_SESSION['user_id']);
        }
        return null;
    }
}

// Exemple d'utilisation :
// $sessionManager = new SessionManager();
// $sessionManager->startSession();
// if ($sessionManager->isLoggedIn()) {
//     $userInfo = $sessionManager->getUserInfo();
// }
