<?php

/**
 * Adminer Plugin: Default DESC Sort
 * 
 * Plugin to force DESC sorting by default on primary key columns
 * Compatible with standard Adminer plugin architecture
 * 
 * @author italic
 * @version 2.1.0
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
        return "2.1.0";
    }
    
    /**
     * Plugin description
     */
    function description() {
        return "Automatically sorts table data in DESC order on primary key column by default";
    }
    
    /**
     * Get the primary key column name for a table
     */
    function getPrimaryKeyColumn($table) {
        global $connection;
        
        if (!$connection) {
            return null;
        }
        
        // Get table indexes to find primary key
        $indexes = indexes($table);
        
        foreach ($indexes as $index) {
            if ($index["type"] == "PRIMARY") {
                // Return the first column of the primary key
                $columns = array_keys($index["columns"]);
                return $columns[0];
            }
        }
        
        // Fallback: check for common primary key names
        $fields = fields($table);
        $commonPkNames = array('id', 'ID', $table . '_id', $table . 'Id');
        
        foreach ($commonPkNames as $pkName) {
            if (isset($fields[$pkName])) {
                return $pkName;
            }
        }
        
        return null;
    }
    
    /**
     * Modifies the default sort order for selections
     * This method is called before displaying table data
     */
    function selectOrderPrint($order, $columns, $indexes) {
        // Get current table name from URL
        $table = $_GET["select"] ?? null;
        
        if (!$table) {
            return false;
        }
        
        // If no order is specified, try to find primary key
        if (empty($order)) {
            $primaryKey = $this->getPrimaryKeyColumn($table);
            
            if ($primaryKey && isset($columns[$primaryKey])) {
                // Force DESC sort on primary key column
                $_GET['order'][0] = $primaryKey;
                $_GET['desc'][0] = '1';
            }
        }
        
        // Let Adminer handle normal display
        return false;
    }
    
    /**
     * Modifies the SELECT query to apply default order
     */
    function selectQuery($query, $start) {
        // If no ORDER BY is present in the query
        if (stripos($query, 'ORDER BY') === false && 
            preg_match('/FROM\s+`?(\w+)`?/i', $query, $matches)) {
            
            $table = $matches[1];
            $primaryKey = $this->getPrimaryKeyColumn($table);
            
            if ($primaryKey) {
                // Add ORDER BY primary_key DESC to the query
                $query = rtrim($query, '; ') . ' ORDER BY `' . $primaryKey . '` DESC';
            }
        }
        
        return $query;
    }
}

// Return plugin instance
return new AdminerDescSort;