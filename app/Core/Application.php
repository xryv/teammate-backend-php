<?php // app/Core/Application.php

// Utilisation de l'espace de noms pour la structuration des composants de l'application
namespace App\Core;

// Importation des classes nécessaires au fonctionnement de l'application
use App\Core\Config;
use App\Core\Router;
use App\Core\Request;
use App\Core\Response;
use App\Core\Database;
use App\Core\Logger;
use App\Core\SessionManager;
use App\Auth\Authenticator;
use App\Auth\Authorization;

/*
Pseudocode:
Initialiser l'application avec le chemin racine et le fichier de configuration.
Configurer l'application (Chargement de la configuration, Initialisation de la base de données, Gestion des sessions, Authentification, Autorisation).
Exécuter l'application en résolvant la route demandée et en gérant les exceptions.
*/

class Application {
    // Déclaration des propriétés de la classe Application
    public static $ROOT_DIR; // Répertoire racine de l'application
    public $router; // Gestionnaire de routes
    public $request; // Gestionnaire de requêtes
    public $response; // Gestionnaire de réponses
    public $db; // Instance de la base de données
    public $logger; // Gestionnaire de logs
    public $session; // Gestionnaire de session
    public $authenticator; // Gestionnaire d'authentification
    public $authorization; // Gestionnaire d'autorisation
    public $config; // Configuration de l'application

    // Constructeur de la classe
    public function __construct($rootPath, $configFile) {
        self::$ROOT_DIR = $rootPath; // Initialisation du répertoire racine
        $this->loadConfig($configFile); // Chargement de la configuration

        // Initialisation des composants de l'application
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->db = Database::getInstance(); // Utilisation de la configuration pour la base de données
        $this->logger = new Logger();
        $this->session = new SessionManager();
        $this->authenticator = new Authenticator($this->db, $this->session);
        $this->authorization = new Authorization();

        // Démarrage de la session
        $this->session->startSession();
    }
    
    // Méthode pour charger la configuration
    private function loadConfig($configFile) {
        Config::load($configFile); // Chargement du fichier de configuration
        // Configuration de la base de données et de l'environnement
        $this->config = [
            'db' => [
                'host' => Config::get('database', 'host'),
                'dbname' => Config::get('database', 'dbname'),
                'user' => Config::get('database', 'user'),
                'password' => Config::get('database', 'password'),
            ],
            'env' => Config::get('app', 'env', 'prod'), // Mode de l'application (dev ou prod)
        ];
    }

    // Méthode pour exécuter l'application
    public function run() {
        try {
            $this->router->dispatch(); // Résolution de la route demandée
        } catch (\Exception $e) {
            $this->handleException($e); // Gestion des exceptions
        }
    }

    // Méthode de gestion des exceptions
    private function handleException(\Exception $e) {
        $this->response->setStatusCode(500); // Code d'erreur 500
        if ($this->config['env'] === 'dev') {
            echo $e->getMessage(); // Affichage de l'erreur en mode développement
        } else {
            $this->logger->error($e->getMessage(), ['exception' => $e]); // Log de l'erreur
            echo "Internal Server Error"; // Message d'erreur générique en mode production
        }
    }

    // Méthode statique pour obtenir le chemin racine
    public static function getRootPath() {
        return self::$ROOT_DIR;
    }
}
