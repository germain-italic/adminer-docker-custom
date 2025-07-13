<?php

/**
 * Adminer Plugin: Default DESC Sort
 * Version compatible avec l'architecture officielle d'Adminer
 */

class AdminerDescSort {
    
    function name() {
        return "Default DESC Sort";
    }
    
    /**
     * Méthode correcte pour modifier les requêtes SELECT dans Adminer
     */
    function selectQuery($query, $start) {
        // Log pour debug
        error_log("AdminerDescSort: Original query = " . $query);
        
        // Si la requête contient déjà ORDER BY, on ne fait rien
        if (stripos($query, 'ORDER BY') !== false) {
            error_log("AdminerDescSort: Query already has ORDER BY, skipping");
            return $query;
        }
        
        // Extraire le nom de la table de la requête
        if (preg_match('/FROM\s+`?(\w+)`?/i', $query, $matches)) {
            $table = $matches[1];
            error_log("AdminerDescSort: Found table = " . $table);
            
            // Ajouter ORDER BY id DESC à la fin de la requête
            $modified_query = rtrim($query, '; ') . ' ORDER BY `id` DESC';
            error_log("AdminerDescSort: Modified query = " . $modified_query);
            
            return $modified_query;
        }
        
        error_log("AdminerDescSort: Could not extract table name, returning original query");
        return $query;
    }
}