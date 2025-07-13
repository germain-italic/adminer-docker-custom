<?php

/**
 * Adminer Plugin: Default DESC Sort
 * Automatically sorts table data in DESC order on primary key column by default
 * 
 * @author italic
 * @version 6.0.0
 * @link https://github.com/germain-italic/adminer-docker-custom
 * @compatible Adminer 4.x+
 */

class AdminerDescSort {
    
    /**
     * Plugin name for display
     */
    function name() {
        return "Default DESC Sort v6.0.0";
    }
    
    /**
     * Modify the SELECT query to add DESC sort on primary key if no ORDER BY is present
     */
    function selectQuery($query, $start, $failed) {
        // Vérifier si la requête est un SELECT et n'a pas déjà d'ORDER BY
        if (preg_match('/^SELECT\s+/i', $query) && !preg_match('/ORDER\s+BY/i', $query)) {
            
            // Extraire le nom de la table depuis la requête
            if (preg_match('/FROM\s+`?([^`\s]+)`?/i', $query, $matches)) {
                $table = $matches[1];
                
                // Obtenir les champs de la table pour trouver la clé primaire
                $fields = fields($table);
                $primary_key = null;
                
                // Chercher la clé primaire
                foreach ($fields as $field_name => $field) {
                    if ($field['primary']) {
                        $primary_key = $field_name;
                        break;
                    }
                }
                
                // Si pas de clé primaire trouvée, utiliser 'id' par défaut
                if (!$primary_key) {
                    // Vérifier si une colonne 'id' existe
                    if (isset($fields['id'])) {
                        $primary_key = 'id';
                    } else {
                        // Prendre la première colonne
                        $primary_key = array_keys($fields)[0] ?? null;
                    }
                }
                
                // Ajouter ORDER BY DESC si une clé primaire a été trouvée
                if ($primary_key) {
                    $query = rtrim($query, '; ') . " ORDER BY `$primary_key` DESC";
                }
            }
        }
        
        return $query;
    }
    
    /**
     * Ajouter du CSS pour indiquer le tri actif
     */
    function head($dark) {
        ?>
        <style>
        /* Indiquer visuellement le tri DESC par défaut */
        .adminer-desc-sort-active {
            background-color: #e8f4fd !important;
        }
        </style>
        <script>
        // Marquer visuellement la colonne triée
        document.addEventListener('DOMContentLoaded', function() {
            var url = window.location.href;
            if (url.indexOf('select=') > -1 && url.indexOf('order') === -1) {
                // Première visite sur une table, marquer la première colonne
                var firstHeader = document.querySelector('table thead th:first-child');
                if (firstHeader) {
                    firstHeader.classList.add('adminer-desc-sort-active');
                }
            }
        });
        </script>
        <?php
        return false; // Continuer avec le head normal
    }
}