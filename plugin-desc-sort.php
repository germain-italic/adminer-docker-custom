<?php

/**
 * Adminer Plugin: Default DESC Sort
 * 
 * Automatically sorts table data in DESC order on primary key column by default
 * 
 * @author italic
 * @version 3.2.0
 * @link https://github.com/germain-italic/adminer-docker-custom
 */

function adminer_object() {
    
    class AdminerDescSort {
        
        function name() {
            return "Default DESC Sort";
        }
        
        function version() {
            return "3.2.0";
        }
        
        /**
         * Modify the SELECT query to add DESC order on primary key
         * This is called when building SELECT queries
         */
        function selectQueryBuild($select, $where, $group, $order, $limit, $page) {
            // Only modify if no order is specified
            if (empty($order)) {
                global $connection, $TABLE;
                
                if ($connection && $TABLE) {
                    try {
                        // Get table structure to find primary key
                        $indexes = indexes($TABLE);
                        $primary_key = null;
                        
                        // Find PRIMARY key
                        foreach ($indexes as $index) {
                            if ($index["type"] == "PRIMARY") {
                                $columns = array_keys($index["columns"]);
                                if (!empty($columns)) {
                                    $primary_key = $columns[0];
                                    break;
                                }
                            }
                        }
                        
                        // Fallback: look for common primary key names
                        if (!$primary_key) {
                            $fields = fields($TABLE);
                            $common_pk_names = ['id', $TABLE . '_id', 'pk'];
                            
                            foreach ($common_pk_names as $name) {
                                if (isset($fields[$name])) {
                                    $primary_key = $name;
                                    break;
                                }
                            }
                        }
                        
                        // Apply DESC order on primary key
                        if ($primary_key) {
                            $order = array($primary_key => true); // true = DESC
                        }
                        
                    } catch (Exception $e) {
                        // Silently fail, don't break Adminer
                    }
                }
            }
            
            // Return all parameters (required by Adminer)
            return array($select, $where, $group, $order, $limit, $page);
        }
    }
    
    return new AdminerDescSort();
}

// Include the original Adminer
include "./adminer.php";