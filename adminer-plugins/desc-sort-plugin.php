<?php

/**
 * Plugin Adminer pour tri automatique DESC sur la clé primaire
 * Basé sur l'API officielle d'Adminer
 */
class AdminerDescSort {
    
    /**
     * Build SQL query used in select
     * @param list<string> $select result of selectColumnsProcess()[0]
     * @param list<string> $where result of selectSearchProcess()
     * @param list<string> $group result of selectColumnsProcess()[1]
     * @param list<string> $order result of selectOrderProcess()
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
        
        $table = $_GET['select'];
        
        // Récupérer les champs de la table
        $fields = fields($table);
        if (!$fields) {
            return "";
        }
        
        // Chercher la clé primaire
        $primary_key = null;
        foreach ($fields as $name => $field) {
            if ($field['primary']) {
                $primary_key = $name;
                break;
            }
        }
        
        // Fallback sur 'id' si pas de clé primaire trouvée
        if (!$primary_key) {
            if (isset($fields['id'])) {
                $primary_key = 'id';
            } else {
                // Prendre la première colonne
                $field_names = array_keys($fields);
                if (!empty($field_names)) {
                    $primary_key = $field_names[0];
                }
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
        if (!empty($where)) {
            $where_clause = " WHERE " . implode(" AND ", $where);
        }
        
        // Construire GROUP BY
        $group_clause = "";
        if (!empty($group)) {
            $group_clause = " GROUP BY " . implode(", ", $group);
        }
        
        // Ajouter le tri DESC sur la clé primaire
        $order_clause = " ORDER BY $escaped_pk DESC";
        
        // Construire la requête complète
        $query = $select_clause . $from_clause . $where_clause . $group_clause . $order_clause;
        
        // Ajouter LIMIT si nécessaire
        if ($limit) {
            $query = limit($query, "", $limit, $page * $limit, " ");
        }
        
        return $query;
    }
}

?>