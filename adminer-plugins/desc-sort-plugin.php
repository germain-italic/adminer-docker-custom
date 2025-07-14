<?php

/**
 * Adminer plugin for automatic DESC sorting on primary keys
 * Compatible with PHP 7.0+ and Adminer 5.x
 */
class AdminerDescSort {
    
    /**
     * Modifies the SELECT query to add ORDER BY DESC on primary key
     * when no order is specified by the user
     * 
     * @param list<string> $select
     * @param list<string> $where  
     * @param list<string> $group
     * @param list<string> $order
     * @param int $limit
     * @param int $page
     * @return string Complete SQL query or empty string for default behavior
     */
    function selectQueryBuild($select, $where, $group, $order, $limit, $page) {
        // If user has defined an order, respect it
        if (!empty($order)) {
            return ""; // Use default query
        }
        
        // Get table name from URL parameters
        $table = isset($_GET["select"]) ? $_GET["select"] : "";
        if (empty($table)) {
            return "";
        }
        
        try {
            // Get database connection using Adminer's function
            $conn = \Adminer\connection();
            if (!$conn) {
                return "";
            }
            
            // Get column information to find primary key
            $result = $conn->query("SHOW COLUMNS FROM " . \Adminer\idf_escape($table));
            if (!$result) {
                return "";
            }
            
            $primary_key = null;
            $fallback_id = null;
            
            // Look for primary key or column containing "id"
            while ($row = $result->fetch_assoc()) {
                if ($row['Key'] === 'PRI') {
                    $primary_key = $row['Field'];
                    break;
                }
                if (stripos($row['Field'], 'id') !== false && !$fallback_id) {
                    $fallback_id = $row['Field'];
                }
            }
            
            $sort_column = $primary_key ? $primary_key : $fallback_id;
            if (!$sort_column) {
                return "";
            }
            
            // Build complete SELECT query with DESC sort
            $select_clause = empty($select) ? "*" : implode(", ", $select);
            $where_clause = empty($where) ? "" : " WHERE " . implode(" AND ", $where);
            $group_clause = empty($group) ? "" : " GROUP BY " . implode(", ", $group);
            $order_clause = " ORDER BY " . \Adminer\idf_escape($sort_column) . " DESC";
            $limit_clause = $limit > 0 ? " LIMIT " . intval($limit) : "";
            $offset_clause = ($page && $limit) ? " OFFSET " . (intval($page) * intval($limit)) : "";
            
            $query = "SELECT " . $select_clause . 
                    " FROM " . \Adminer\idf_escape($table) .
                    $where_clause . 
                    $group_clause . 
                    $order_clause . 
                    $limit_clause . 
                    $offset_clause;
            
            return $query;
            
        } catch (Exception $e) {
            // Return empty string to fall back to default behavior
            return "";
        }
    }
}