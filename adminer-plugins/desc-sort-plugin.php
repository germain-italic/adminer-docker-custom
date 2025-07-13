<?php

/**
 * Adminer Plugin: Default DESC Sort
 * Compatible avec Adminer 5.3.0
 */

class AdminerDescSort {
    
    function name() {
        return "Default DESC Sort v5.3.0";
    }
    
    /**
     * Méthode pour Adminer 5.x - modification de la requête SELECT
     */
    function selectQuery($query, $start) {
        error_log("AdminerDescSort: selectQuery called with: " . $query);
        
        // Si ORDER BY existe déjà, ne rien faire
        if (stripos($query, 'ORDER BY') !== false) {
            error_log("AdminerDescSort: ORDER BY already exists, skipping");
            return $query;
        }
        
        // Ajouter ORDER BY id DESC
        $modified = rtrim($query, '; ') . ' ORDER BY `id` DESC';
        error_log("AdminerDescSort: Modified query: " . $modified);
        
        return $modified;
    }
}