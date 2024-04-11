### Structure du Projet

J'ai commencé par structurer mon projet en créant des répertoires spécifiques pour les différents aspects de l'application, tels que les modèles, les vues, le cœur de l'application, la configuration et l'accès public. Cette structure me permet de séparer clairement la logique de l'application, les interactions avec la base de données, et l'interface utilisateur, facilitant ainsi le développement et la maintenance.

- **`app\Models`**: Contient les modèles de l'application, notamment `User.php` pour la gestion des utilisateurs.
- **`app\Core`**: Héberge les fichiers essentiels du cœur de l'application, comme `Application.php` pour le traitement des requêtes et la logique générale.
- **`app\Views`**: Regroupe les vues de l'application, divisées en sous-répertoires pour les administrateurs et les utilisateurs.
- **`config`**: Contient les fichiers de configuration, y compris `Database.php` pour la connexion à la base de données.
- **`public`**: Sert de point d'entrée public à l'application, avec `index.php` gérant le routage initial.

### Routage et Contrôleurs

Pour gérer les différentes routes de l'application, j'ai mis en place un système de routage simple dans `public\index.php`, qui dirige les requêtes vers les contrôleurs appropriés en fonction de l'URL. Ce système me permet de définir clairement comment les requêtes sont traitées et de répondre efficacement aux actions des utilisateurs.

### Base de Données et Sécurité

La connexion à la base de données est gérée par un modèle Singleton dans `config\Database.php`, assurant une unique instance de connexion pour toute l'application. J'accorde une attention particulière à la sécurité des données, en utilisant des requêtes préparées pour éviter les injections SQL et en validant soigneusement les entrées utilisateur.

### Organisation des Scripts SQL

Les fichiers SQL, tels que les schémas de tables et les scripts de peuplement, sont organisés dans un répertoire `sql` à la racine du projet. Cette organisation me permet de gérer facilement les structures de données et les données initiales, facilitant le déploiement et les tests.

