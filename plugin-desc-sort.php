<?php

/**
 * Adminer Plugin: Default DESC Sort
 * 
 * Plugin pour forcer le tri DESC par défaut sur la colonne 'id'
 * Compatible avec l'architecture standard des plugins Adminer
 * 
 * @author italic
 * @version 2.0.0
 * @link https://github.com/germain-italic/adminer-docker-custom
 */

class AdminerDescSort {
    
    /**
     * Nom du plugin
     */
    function name() {
        return "Default DESC Sort";
    }
    
    /**
     * Version du plugin
     */
    function version() {
        return "2.0.0";
    }
    
    /**
     * Description du plugin
     */
    function description() {
        return "Automatically sorts table data in DESC order on 'id' column by default";
    }
    
    /**
     * Modifie l'ordre de tri par défaut pour les sélections
     * Cette méthode est appelée avant l'affichage des données d'une table
     */
    function selectOrderPrint($order, $columns, $indexes) {
        // Si aucun ordre n'est spécifié et qu'on a une colonne 'id'
        if (empty($order) && isset($columns['id'])) {
            // Force le tri DESC sur la colonne 'id'
            $_GET['order'][0] = 'id';
            $_GET['desc'][0] = '1';
        }
        
        // Laisse Adminer gérer l'affichage normal
        return false;
    }
    
    /**
     * Modifie la requête SELECT pour appliquer l'ordre par défaut
     */
    function selectQuery($query, $start) {
        // Si aucun ORDER BY n'est présent dans la requête et qu'on a une table avec 'id'
        if (stripos($query, 'ORDER BY') === false && 
            preg_match('/FROM\s+`?(\w+)`?/i', $query, $matches)) {
            
            $table = $matches[1];
            
            // Vérifie si la table a une colonne 'id'
            global $connection;
            if ($connection) {
                $fields = fields($table);
                if (isset($fields['id'])) {
                    // Ajoute ORDER BY id DESC à la requête
                    $query = rtrim($query, '; ') . ' ORDER BY `id` DESC';
                }
            }
        }
        
        return $query;
    }
}

// Retourne une instance du plugin
return new AdminerDescSort;