<?php

/**
 * Plugin Adminer pour tri automatique DESC sur la clé primaire
 * Compatible PHP 7.0+ et API Adminer standard
 */
class AdminerDescSort {
    
    /**
     * Construit la requête SQL avec tri DESC automatique sur la clé primaire
     * @param array $select colonnes sélectionnées
     * @param array $where conditions WHERE
     * @param array $group colonnes GROUP BY
     * @param array $order colonnes ORDER BY
     * @param int $limit limite de résultats
     * @param int $page numéro de page
     * @return string requête SQL complète ou chaîne vide pour utiliser la requête par défaut
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
            // Récupérer les champs de la table
            $fields = fields($table);
            if (!$fields) {
                return "";
            }
            
            // Chercher la clé primaire
            $primary_key = null;
            foreach ($fields as $name => $field) {
                if ($field["primary"]) {
                    $primary_key = $name;
                    break;
                }
            }
            
            // Si pas de clé primaire trouvée, chercher une colonne "id"
            if (!$primary_key) {
                foreach ($fields as $name => $field) {
                    if (strtolower($name) === 'id' || strpos(strtolower($name), 'id') !== false) {
                        $primary_key = $name;
                        break;
                    }
                }
            }
            
            // Si toujours pas de clé trouvée, utiliser la requête par défaut
            if (!$primary_key) {
                return "";
            }
            
            // Construire la requête avec ORDER BY DESC sur la clé primaire
            $select_clause = empty($select) ? "*" : implode(", ", $select);
            $where_clause = empty($where) ? "" : " WHERE " . implode(" AND ", $where);
            $group_clause = empty($group) ? "" : " GROUP BY " . implode(", ", $group);
            $order_clause = " ORDER BY " . idf_escape($primary_key) . " DESC";
            $limit_clause = $limit ? " LIMIT " . intval($limit) : "";
            $offset_clause = ($page && $limit) ? " OFFSET " . (intval($page) * intval($limit)) : "";
            
            return "SELECT $select_clause FROM " . table($table) . $where_clause . $group_clause . $order_clause . $limit_clause . $offset_clause;
            
        } catch (Exception $e) {
            // En cas d'erreur, utiliser la requête par défaut
            return "";
        }
    }
}

?>