<?php

namespace Adminer;

/**
 * Plugin Adminer pour tri DESC automatique sur clé primaire
 * Compatible PHP 7.0+ et Adminer 4.x/5.x
 */
class AdminerDescSort {
    
    /**
     * Modifie la requête SELECT pour ajouter ORDER BY DESC sur la clé primaire
     * quand aucun ordre n'est spécifié par l'utilisateur
     */
    function selectQueryBuild(array $select, array $where, array $group, array $order, int $limit, ?int $page): string {
        global $connection;
        
        // Si l'utilisateur a défini un ordre, on le respecte
        if (isset($_GET["order"]) && !empty($_GET["order"])) {
            return ""; // Utilise la requête par défaut
        }
        
        // Récupère le nom de la table depuis l'URL
        $table = $_GET["select"] ?? "";
        if (empty($table)) {
            return "";
        }
        
        try {
            // Récupère les informations des colonnes
            $result = $connection->query("SHOW COLUMNS FROM `" . str_replace("`", "``", $table) . "`");
            if (!$result) {
                return "";
            }
            
            $primary_key = null;
            $fallback_id = null;
            
            // Cherche la clé primaire ou une colonne "id"
            while ($row = $result->fetch_assoc()) {
                if ($row['Key'] === 'PRI') {
                    $primary_key = $row['Field'];
                    break;
                }
                if (stripos($row['Field'], 'id') !== false && !$fallback_id) {
                    $fallback_id = $row['Field'];
                }
            }
            
            $sort_column = $primary_key ?: $fallback_id;
            if (!$sort_column) {
                return "";
            }
            
            // Construction de la requête SELECT complète
            $select_clause = empty($select) ? "*" : implode(", ", $select);
            $where_clause = empty($where) ? "" : " WHERE " . implode(" AND ", $where);
            $group_clause = empty($group) ? "" : " GROUP BY " . implode(", ", $group);
            $order_clause = " ORDER BY `" . str_replace("`", "``", $sort_column) . "` DESC";
            $limit_clause = $limit > 0 ? " LIMIT " . intval($limit) : "";
            $offset_clause = ($page && $limit) ? " OFFSET " . (intval($page) * intval($limit)) : "";
            
            $query = "SELECT " . $select_clause . 
                    " FROM `" . str_replace("`", "``", $table) . "`" .
                    $where_clause . 
                    $group_clause . 
                    $order_clause . 
                    $limit_clause . 
                    $offset_clause;
            
            return $query;
            
        } catch (Exception $e) {
            // En cas d'erreur, utilise la requête par défaut
            return "";
        }
    }
}