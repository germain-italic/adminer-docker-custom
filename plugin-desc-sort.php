<?php

/**
 * Adminer Plugin: Default DESC Sort
 * Automatically sorts table data in DESC order on primary key column by default
 * 
 * @author italic
 * @version 4.0.0
 * @link https://github.com/germain-italic/adminer-docker-custom
 * @compatible Adminer 5.3.0
 */

class AdminerDescSort {
    
    /**
     * Plugin name for display
     */
    function name() {
        return "Default DESC Sort v4.0.0";
    }
    
    /**
     * Intercept all SQL queries and modify SELECT queries to add DESC sort
     */
    function query($query, $start = 0) {
        error_log("AdminerDescSort: Intercepting query: " . substr($query, 0, 100) . "...");
        
        // Only modify SELECT queries
        if (preg_match('/^\s*SELECT\s+/i', $query)) {
            // Check if ORDER BY already exists
            if (!preg_match('/\bORDER\s+BY\b/i', $query)) {
                // Try to detect table name from FROM clause
                if (preg_match('/\bFROM\s+`?([a-zA-Z_][a-zA-Z0-9_]*)`?\s*/i', $query, $matches)) {
                    $table = $matches[1];
                    error_log("AdminerDescSort: Found table: $table");
                    
                    // Add ORDER BY id DESC (or table_id DESC)
                    $primary_key = 'id';
                    
                    // Check if query contains LIMIT
                    if (preg_match('/\bLIMIT\s+/i', $query)) {
                        // Insert ORDER BY before LIMIT
                        $modified_query = preg_replace('/(\s+LIMIT\s+)/i', " ORDER BY `$primary_key` DESC$1", $query);
                    } else {
                        // Add ORDER BY at the end
                        $modified_query = rtrim($query, '; ') . " ORDER BY `$primary_key` DESC";
                    }
                    
                    error_log("AdminerDescSort: Modified query: " . substr($modified_query, 0, 200) . "...");
                    
                    // Execute the modified query
                    global $connection;
                    return $connection->query($modified_query);
                }
            }
        }
        
        // Return false to let Adminer handle the original query
        return false;
    }
}