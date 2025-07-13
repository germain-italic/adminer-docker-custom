<?php

/**
 * Adminer Plugin: Default DESC Sort
 * Automatically sorts table data in DESC order on primary key column by default
 * 
 * @author italic
 * @version 5.0.0
 * @link https://github.com/germain-italic/adminer-docker-custom
 * @compatible Adminer 4.x+
 */

class AdminerDescSort {
    
    /**
     * Plugin name for display
     */
    function name() {
        return "Default DESC Sort v5.0.0";
    }
    
    /**
     * Add JavaScript to automatically redirect to DESC sort on first load
     */
    function head() {
        ?>
        <script>
        (function() {
            // Vérifier si on est sur une page de sélection de table
            if (window.location.href.indexOf('select=') > -1 && 
                window.location.href.indexOf('order') === -1) {
                
                // Attendre que la page soit chargée
                document.addEventListener('DOMContentLoaded', function() {
                    // Chercher le lien de tri sur la colonne 'id' ou première colonne
                    var headers = document.querySelectorAll('table thead th a');
                    var idLink = null;
                    
                    // Chercher spécifiquement la colonne 'id'
                    for (var i = 0; i < headers.length; i++) {
                        var header = headers[i];
                        if (header.textContent.toLowerCase() === 'id' || 
                            header.textContent.toLowerCase().indexOf('id') > -1) {
                            idLink = header;
                            break;
                        }
                    }
                    
                    // Si pas trouvé, prendre la première colonne
                    if (!idLink && headers.length > 0) {
                        idLink = headers[0];
                    }
                    
                    if (idLink) {
                        // Modifier l'URL pour ajouter DESC
                        var href = idLink.href;
                        if (href.indexOf('order') === -1) {
                            var separator = href.indexOf('?') > -1 ? '&' : '?';
                            var columnName = idLink.textContent.toLowerCase();
                            
                            // Extraire le nom de la colonne depuis l'URL ou le texte
                            var match = href.match(/select=([^&]+)/);
                            if (match) {
                                // Rediriger automatiquement avec le tri DESC
                                var newUrl = href + separator + 'order%5B0%5D=' + encodeURIComponent(columnName) + '&desc%5B0%5D=1';
                                console.log('AdminerDescSort: Redirecting to', newUrl);
                                window.location.href = newUrl;
                            }
                        }
                    }
                });
            }
        })();
        </script>
        <?php
    }
    
    /**
     * Modifier les liens de tri pour commencer par DESC
     */
    function selectLink($val, $field) {
        // Si aucun tri n'est défini, le premier clic doit être DESC
        if (!isset($_GET["order"])) {
            return null; // Laisser Adminer gérer mais on va intercepter avec JS
        }
        return false; // Comportement normal
    }
}