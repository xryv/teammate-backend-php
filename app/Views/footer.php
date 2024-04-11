<?php
// footer.php
?>

        </main> <!-- Fin du contenu principal -->

        <footer class="bg-gray-800 text-white p-4">
            <div class="container mx-auto text-center">
                <p>Teammate © <?php echo date("Y"); ?>. Tous droits réservés.</p>

                <!-- Liens du pied de page ou informations de contact -->
                <ul class="flex justify-center mt-4">
                    <li><a href="/about" class="mx-2">À propos</a></li>
                    <li><a href="/privacy" class="mx-2">Politique de confidentialité</a></li>
                    <li><a href="/terms" class="mx-2">Conditions d'utilisation</a></li>
                    <li><a href="/contact" class="mx-2">Contact</a></li>
                </ul>

                <!-- Réseaux sociaux ou liens externes -->
                <div class="social-links mt-4">
                    <!-- Icônes des réseaux sociaux -->
                </div>
            </div>
        </footer>

        <!-- Scripts JS au besoin -->
        <script src="/src/js/fetch_countries.js"></script>
        <script src="//unpkg.com/alpinejs" defer></script>
    </body>
</html>
