<?php

/**
 * Adminer Plugin: Default DESC Sort
 * Automatically sorts table data in DESC order on primary key column by default
 * 
 * @author italic
 * @version 7.0.0
 * @link https://github.com/germain-italic/adminer-docker-custom
 * @compatible Adminer 4.x+
 */

class AdminerDescSort {
    
    /**
     * Plugin name for display
     */
    function name() {
        return "Default DESC Sort v7.0.0";
    }
    
    /**
     * Modify the SELECT query to add DESC sort on primary key if no ORDER BY is present
     * This method is called before query execution
     */
    function selectQuery($query, $start, $failed = false) {
        // Debug: log the original query
        error_log("AdminerDescSort: Original query: " . $query);
        
        // Only process SELECT queries that don't already have ORDER BY
        if (preg_match('/^SELECT\s+/i', trim($query)) && !preg_match('/\bORDER\s+BY\b/i', $query)) {
            
            // Extract table name from FROM clause
            if (preg_match('/\bFROM\s+`?([^`\s\(\)]+)`?/i', $query, $matches)) {
                $table = $matches[1];
                error_log("AdminerDescSort: Found table: " . $table);
                
                // Get table fields to find primary key
                try {
                    $fields = fields($table);
                    $primary_key = null;
                    
                    // Look for primary key
                    foreach ($fields as $field_name => $field) {
                        if (isset($field['primary']) && $field['primary']) {
                            $primary_key = $field_name;
                            break;
                        }
                    }
                    
                    // Fallback to 'id' if no primary key found
                    if (!$primary_key && isset($fields['id'])) {
                        $primary_key = 'id';
                    }
                    
                    // Fallback to first column if still no key found
                    if (!$primary_key && !empty($fields)) {
                        $primary_key = array_keys($fields)[0];
                    }
                    
                    if ($primary_key) {
                        // Add ORDER BY clause
                        $query = rtrim($query, '; ') . " ORDER BY `$primary_key` DESC";
                        error_log("AdminerDescSort: Modified query: " . $query);
                    }
                    
                } catch (Exception $e) {
                    error_log("AdminerDescSort: Error getting fields for table $table: " . $e->getMessage());
                }
            }
        }
        
        return $query;
    }
    
    /**
     * Alternative approach: modify the query building process
     */
    function selectQueryBuild($select, $where, $group, $order, $limit, $page) {
        // If no order is specified, add default DESC order on primary key
        if (empty($order)) {
            // Get current table from URL
            $table = $_GET['select'] ?? '';
            if ($table) {
                try {
                    $fields = fields($table);
                    $primary_key = null;
                    
                    // Find primary key
                    foreach ($fields as $field_name => $field) {
                        if (isset($field['primary']) && $field['primary']) {
                            $primary_key = $field_name;
                            break;
                        }
                    }
                    
                    // Fallback to 'id'
                    if (!$primary_key && isset($fields['id'])) {
                        $primary_key = 'id';
                    }
                    
                    if ($primary_key) {
                        $order = array("`$primary_key` DESC");
                        error_log("AdminerDescSort: Added default order: " . implode(', ', $order));
                    }
                } catch (Exception $e) {
                    error_log("AdminerDescSort: Error in selectQueryBuild: " . $e->getMessage());
                }
            }
        }
        
        // Call parent method to build the query
        return parent::selectQueryBuild($select, $where, $group, $order, $limit, $page);
    }
}