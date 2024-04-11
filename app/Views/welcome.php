<?php include 'header.php'; ?>

<!-- Conteneur principal pour la page d'accueil -->
<div class="container mx-auto mt-10">
    <!-- Titre de bienvenue centré avec une grande taille de police -->
    <h1 class="text-3xl font-bold text-center">
        Welcome to the Teammate App
    </h1>
    <!-- Grille pour les images de fonctionnalités -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">

    <?php 
        // Ensure that $images is defined and is an array.
        if (!isset($images) || !is_array($images)) {
            $images = []; // Define $images as an empty array if it's not set or not an array.
            // You might want to handle this error, e.g., by logging it or displaying a user-friendly message.
        }
        

        foreach ($images as $page => $image): ?>
            <div class="max-w-sm rounded overflow-hidden shadow-lg" style="background-image: linear-gradient(to bottom, purple, darkblue, pink);">
                <!-- Image représentant la fonctionnalité -->
                <img class="w-full" src="<?= htmlspecialchars($image) ?>" alt="<?= htmlspecialchars($page) ?>">
                <div class="px-6 py-4">
                    <!-- Titre de la fonctionnalité -->
                    <div class="font-bold text-xl mb-2"><?= htmlspecialchars(ucfirst($page)) ?></div>
                    <!-- Description de la fonctionnalité -->
                    <p class="text-gray-700 text-base">
                        Explore <?= htmlspecialchars($page) ?> features of our application.
                    </p>
                </div>
                <div class="px-6 pt-4 pb-2">
                    <!-- Lien pour naviguer vers la fonctionnalité -->
                    <a href="/<?= htmlspecialchars($page) ?>" class="inline-block bg-blue-500 rounded-full px-3 py-1 text-sm font-semibold text-white mr-2 mb-2">Go to <?= htmlspecialchars(ucfirst($page)) ?></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'footer.php'; ?>
