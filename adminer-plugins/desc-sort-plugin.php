<?php

/**
 * Adminer Plugin: Default DESC Sort
 * Automatically sorts table data in DESC order on primary key column by default
 * 
 * @author italic
 * @version 4.1.0
 * @link https://github.com/germain-italic/adminer-docker-custom
 * @compatible Adminer 4.x
 */

class AdminerDescSort {
    
    /**
     * Plugin name for display
     */
    function name() {
        return "Default DESC Sort v4.1.0";
    }
    
    /**
     * Modify the table select query to add default DESC sort
     */
    function selectQueryBuild($select, $where, $group, $order, $limit, $page) {
        // Si aucun tri n'est spécifié, ajouter un tri DESC sur 'id'
        if (!$order) {
            // Essayer de détecter la clé primaire
            global $connection;
            $table_status = table_status();
            if ($table_status) {
                $table = array_keys($table_status)[0];
                $fields = fields($table);
                
                // Chercher une colonne 'id' ou la première clé primaire
                $primary_key = null;
                foreach ($fields as $name => $field) {
                    if ($name === 'id') {
                        $primary_key = 'id';
                        break;
                    }
                    if ($field['primary']) {
                        $primary_key = $name;
                        break;
                    }
                }
                
                if ($primary_key) {
                    $order = array($primary_key => 'DESC');
                }
            }
        }
        
        return "";
    }
    
    /**
     * Modifier l'URL de tri par défaut
     */
    function selectLink($val, $field) {
        if (!$_GET["order"]) {
            // Si aucun tri n'est défini, on veut que le premier clic soit DESC
            return null;
        }
        return false;
    }
    
    /**
     * Intercepter et modifier les requêtes SELECT
     */
    function selectQuery($query, $start, $failed = false) {
        // Vérifier si c'est une requête de sélection de table sans ORDER BY
        if (preg_match('/^SELECT .+ FROM `([^`]+)`(?:\s+WHERE .+)?$/i', $query, $matches) && 
            !preg_match('/ORDER BY/i', $query) && 
            !$_GET["order"]) {
            
            $table = $matches[1];
            
            // Ajouter ORDER BY id DESC par défaut
            $modified_query = rtrim($query, '; ') . " ORDER BY `id` DESC";
            
            error_log("AdminerDescSort: Modified query from '$query' to '$modified_query'");
            
            return $modified_query;
        }
        
        return $query;
    }
}