<?php
// app/Auth/Authorization.php
namespace App\Auth; 

class Authorization {
    /**
     * Vérifie si l'utilisateur connecté possède un rôle spécifique.
     * 
     * @param string $requiredRole Le rôle requis.
     * @return bool Retourne true si l'utilisateur a le rôle requis, sinon false.
     */
    public static function hasRole($requiredRole): bool {
        // Vérifie si la session 'role' est définie et correspond au rôle requis
        return isset($_SESSION['role']) && $_SESSION['role'] === $requiredRole;
    }

    /**
     * Contrôle d'accès basé sur le rôle.
     * 
     * @param string $requiredRole Le rôle requis pour accéder à la ressource.
     */
    public static function accessControl($requiredRole) {
        if (!self::hasRole($requiredRole)) {
            // Redirige vers la page de connexion ou une page d'erreur d'accès refusé
            header('Location: /login');
            exit;
        }
    }
}
