<?php

/**
 * Plugin Adminer pour tri automatique DESC sur la clé primaire
 * Compatible PHP 7.0+ et API Adminer standard
 */
class AdminerDescSort {
    
    /**
     * Construit la requête SQL avec tri DESC automatique sur la clé primaire
     */
    function selectQueryBuild($select, $where, $group, $order, $limit, $page) {
        // Si un ordre est déjà défini, utiliser la requête par défaut
        if (!empty($order)) {
            return "";
        }
        
        // Vérifier qu'on est bien sur une table
        if (!isset($_GET["select"]) || $_GET["select"] == "") {
            return "";
        }
        
        $table = $_GET["select"];
        
        try {
            // Accéder aux fonctions via l'instance globale
            global $connection;
            if (!$connection) {
                return "";
            }
            
            // Récupérer les champs de la table via une requête directe
            $result = $connection->query("SHOW COLUMNS FROM " . "`" . str_replace("`", "``", $table) . "`");
            if (!$result) {
                return "";
            }
            
            $primary_key = null;
            $id_column = null;
            
            // Analyser les colonnes
            while ($row = $result->fetch_assoc()) {
                $field_name = $row['Field'];
                $key = $row['Key'];
                
                // Chercher la clé primaire
                if ($key === 'PRI') {
                    $primary_key = $field_name;
                    break;
                }
                
                // Chercher une colonne contenant "id" comme fallback
                if (!$id_column && (strtolower($field_name) === 'id' || strpos(strtolower($field_name), 'id') !== false)) {
                    $id_column = $field_name;
                }
            }
            
            // Utiliser la clé primaire ou la colonne ID trouvée
            $sort_column = $primary_key ?: $id_column;
            
            if (!$sort_column) {
                return "";
            }
            
            // Construire la requête avec ORDER BY DESC
            $select_clause = empty($select) ? "*" : implode(", ", $select);
            $where_clause = empty($where) ? "" : " WHERE " . implode(" AND ", $where);
            $group_clause = empty($group) ? "" : " GROUP BY " . implode(", ", $group);
            $order_clause = " ORDER BY `" . str_replace("`", "``", $sort_column) . "` DESC";
            $limit_clause = $limit ? " LIMIT " . intval($limit) : "";
            $offset_clause = ($page && $limit) ? " OFFSET " . (intval($page) * intval($limit)) : "";
            
            return "SELECT $select_clause FROM `" . str_replace("`", "``", $table) . "`" . $where_clause . $group_clause . $order_clause . $limit_clause . $offset_clause;
            
        } catch (Exception $e) {
            // En cas d'erreur, utiliser la requête par défaut
            return "";
        }
    }
}

?>