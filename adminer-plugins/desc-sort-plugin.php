<?php

/**
 * Plugin Adminer pour tri automatique DESC sur la clé primaire
 * Compatible avec PHP 7.0+
 */
class AdminerDescSort {
    
    /**
     * Build SQL query used in select
     * @param array $select result of selectColumnsProcess()[0]
     * @param array $where result of selectSearchProcess()
     * @param array $group result of selectColumnsProcess()[1]
     * @param array $order result of selectOrderProcess()
     * @param int $limit result of selectLimitProcess()
     * @param int $page index of page starting at zero
     * @return string empty string to use default query
     */
    function selectQueryBuild($select, $where, $group, $order, $limit, $page) {
        // Si un tri est déjà défini, on utilise la requête par défaut
        if (!empty($order)) {
            return "";
        }
        
        // Vérifier qu'on est bien sur une page de sélection de table
        if (!isset($_GET['select']) || empty($_GET['select'])) {
            return "";
        }
        
        // Vérifier que $select n'est pas vide
        if (empty($select) || !is_array($select)) {
            return "";
        }
        
        $table = $_GET['select'];
        
        try {
            // Utiliser connection() pour accéder à la base de données
            $connection = connection();
            if (!$connection) {
                return "";
            }
            
            // Récupérer les champs de la table
            $fields = fields($table);
            if (!$fields) {
                return "";
            }
            
            // Chercher la clé primaire
            $primary_key = null;
            foreach ($fields as $name => $field) {
                if (!empty($field['primary'])) {
                    $primary_key = $name;
                    break;
                }
            }
            
            // Fallback sur 'id' si pas de clé primaire trouvée
            if (!$primary_key && isset($fields['id'])) {
                $primary_key = 'id';
            }
            
            // Si toujours pas de clé primaire, prendre la première colonne
            if (!$primary_key) {
                $field_names = array_keys($fields);
                if (!empty($field_names)) {
                    $primary_key = $field_names[0];
                }
            }
            
            if (!$primary_key) {
                return "";
            }
            
            // Construire la requête avec ORDER BY DESC
            $escaped_table = table($table);
            $escaped_pk = idf_escape($primary_key);
            
            // Construire SELECT
            $select_clause = "SELECT " . implode(", ", $select);
            
            // Construire FROM
            $from_clause = " FROM $escaped_table";
            
            // Construire WHERE
            $where_clause = "";
            if (!empty($where) && is_array($where)) {
                $where_clause = " WHERE " . implode(" AND ", $where);
            }
            
            // Construire GROUP BY
            $group_clause = "";
            if (!empty($group) && is_array($group)) {
                $group_clause = " GROUP BY " . implode(", ", $group);
            }
            
            // Ajouter le tri DESC sur la clé primaire
            $order_clause = " ORDER BY $escaped_pk DESC";
            
            // Construire la requête complète
            $query = $select_clause . $from_clause . $where_clause . $group_clause . $order_clause;
            
            // Ajouter LIMIT si nécessaire
            if ($limit > 0) {
                $offset = $page ? $page * $limit : 0;
                $query = limit($query, "", $limit, $offset, " ");
            }
            
            return $query;
            
        } catch (Exception $e) {
            // En cas d'erreur, retourner une chaîne vide pour utiliser la requête par défaut
            return "";
        }
    }
}

?>