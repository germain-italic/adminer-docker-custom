<?php

/**
 * Adminer Plugin: Default DESC Sort
 * Automatically sorts table data in DESC order on primary key column by default
 * 
 * @author italic
 * @version 3.0.0
 */

class AdminerDescSort {
    
    function name() {
        return "Default DESC Sort";
    }
    
    function version() {
        return "3.0.0";
    }
    
    function selectQueryBuild($select, $where, $group, $order, $limit, $page) {
        // Si il y a déjà un ORDER BY, on ne fait rien
        if (!empty($order)) {
            return array($select, $where, $group, $order, $limit, $page);
        }
        
        // Récupère la table courante
        global $TABLE;
        if (empty($TABLE)) {
            return array($select, $where, $group, $order, $limit, $page);
        }
        
        // Trouve la clé primaire
        $primary_key = $this->findPrimaryKey($TABLE);
        
        if ($primary_key) {
            $order = array($primary_key => 'DESC');
        }
        
        return array($select, $where, $group, $order, $limit, $page);
    }
    
    private function findPrimaryKey($table) {
        try {
            // Méthode 1: Cherche dans les indexes
            $indexes = indexes($table);
            foreach ($indexes as $index) {
                if ($index['type'] === 'PRIMARY') {
                    $columns = array_keys($index['columns']);
                    if (!empty($columns)) {
                        return $columns[0];
                    }
                }
            }
            
            // Méthode 2: Cherche AUTO_INCREMENT
            $fields = fields($table);
            foreach ($fields as $name => $field) {
                if ($field['auto_increment']) {
                    return $name;
                }
            }
            
            // Méthode 3: Noms courants
            if (isset($fields['id'])) return 'id';
            if (isset($fields[$table . '_id'])) return $table . '_id';
            
        } catch (Exception $e) {
            // Ignore les erreurs
        }
        
        return null;
    }
}