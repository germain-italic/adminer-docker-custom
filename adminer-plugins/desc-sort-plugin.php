<?php

/**
 * Plugin Adminer pour tri automatique DESC sur la clé primaire
 * Version simplifiée compatible avec toutes les versions d'Adminer
 */
class AdminerDescSort {
    
    /**
     * Modifie l'ordre par défaut en ajoutant un script JavaScript
     */
    function head() {
        ?>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Vérifier si on est sur une page de sélection de table
            if (window.location.search.includes('select=') && !window.location.search.includes('order')) {
                // Chercher la première colonne qui ressemble à un ID
                var firstHeader = document.querySelector('table thead th:first-child a');
                if (firstHeader) {
                    var columnName = firstHeader.textContent.trim();
                    // Si c'est probablement un ID, ajouter le tri DESC
                    if (columnName.toLowerCase().includes('id') || columnName === 'id') {
                        var currentUrl = window.location.href;
                        var separator = currentUrl.includes('?') ? '&' : '?';
                        var newUrl = currentUrl + separator + 'order%5B0%5D=' + encodeURIComponent(columnName) + '&desc%5B0%5D=1';
                        window.location.href = newUrl;
                    }
                }
            }
        });
        </script>
        <?php
    }
    
    /**
     * Alternative: Modifier l'URL par défaut pour inclure l'ordre DESC
     */
    function selectLink($val, $field) {
        // Si c'est la première fois qu'on accède à une table sans ordre
        if (isset($_GET['select']) && !isset($_GET['order']) && $field == 'id') {
            // Ajouter automatiquement l'ordre DESC sur l'ID
            $_GET['order'][0] = 'id';
            $_GET['desc'][0] = '1';
        }
        return parent::selectLink($val, $field);
    }
}

?>