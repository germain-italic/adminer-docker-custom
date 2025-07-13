<?php

/**
 * Adminer Plugin: Default DESC Sort
 * 
 * Plugin to force DESC sorting by default on the 'id' column
 * Compatible with standard Adminer plugin architecture
 * 
 * @author italic
 * @version 2.0.0
 * @link https://github.com/germain-italic/adminer-docker-custom
 */

class AdminerDescSort {
    
    /**
     * Plugin name
     */
    function name() {
        return "Default DESC Sort";
    }
    
    /**
     * Plugin version
     */
    function version() {
        return "2.0.0";
    }
    
    /**
     * Plugin description
     */
    function description() {
        return "Automatically sorts table data in DESC order on 'id' column by default";
    }
    
    /**
     * Modifies the default sort order for selections
     * This method is called before displaying table data
     */
    function selectOrderPrint($order, $columns, $indexes) {
        // If no order is specified and we have an 'id' column
        if (empty($order) && isset($columns['id'])) {
            // Force DESC sort on 'id' column
            $_GET['order'][0] = 'id';
            $_GET['desc'][0] = '1';
        }
        
        // Let Adminer handle normal display
        return false;
    }
    
    /**
     * Modifies the SELECT query to apply default order
     */
    function selectQuery($query, $start) {
        // If no ORDER BY is present in the query and we have a table with 'id'
        if (stripos($query, 'ORDER BY') === false && 
            preg_match('/FROM\s+`?(\w+)`?/i', $query, $matches)) {
            
            $table = $matches[1];
            
            // Check if the table has an 'id' column
            global $connection;
            if ($connection) {
                $fields = fields($table);
                if (isset($fields['id'])) {
                    // Add ORDER BY id DESC to the query
                    $query = rtrim($query, '; ') . ' ORDER BY `id` DESC';
                }
            }
        }
        
        return $query;
    }
}

// Return plugin instance
return new AdminerDescSort;