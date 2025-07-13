<?php

/**
 * Adminer Plugin: Default DESC Sort
 * Automatically sorts table data in DESC order on primary key column by default
 * 
 * @author italic
 * @version 3.2.0
 * @link https://github.com/germain-italic/adminer-docker-custom
 */

class AdminerDescSort {
    
    /**
     * Plugin name for display
     */
    function name() {
        return "Default DESC Sort";
    }
    
    /**
     * Plugin version
     */
    function version() {
        return "3.2.0";
    }
    
    /**
     * Modify the SELECT query to add default DESC ordering on primary key
     */
    function selectQueryBuild($select, $where, $group, $order, $limit, $page) {
        try {
            // Get current table from global variables
            global $TABLE;
            $table = $TABLE;
            
            if (empty($table)) {
                return array($select, $where, $group, $order, $limit, $page);
            }
            
            // If there's already an ORDER BY clause, don't modify
            if (!empty($order)) {
                return array($select, $where, $group, $order, $limit, $page);
            }
            
            // Get database connection
            global $connection;
            if (!$connection) {
                return array($select, $where, $group, $order, $limit, $page);
            }
            
            // Find primary key column
            $primary_key = $this->findPrimaryKey($table, $connection);
            
            if ($primary_key) {
                $order = array($primary_key => 'DESC');
                error_log("AdminerDescSort: Applied DESC sort on '$primary_key' for table '$table'");
            }
            
        } catch (Exception $e) {
            error_log("AdminerDescSort Error: " . $e->getMessage());
        }
        
        return array($select, $where, $group, $order, $limit, $page);
    }
    
    /**
     * Find the primary key column for a table
     */
    private function findPrimaryKey($table, $connection) {
        try {
            // Method 1: Check indexes for PRIMARY KEY
            $indexes = indexes($table);
            if ($indexes) {
                foreach ($indexes as $index) {
                    if ($index['type'] === 'PRIMARY') {
                        $columns = array_keys($index['columns']);
                        if (!empty($columns)) {
                            return $columns[0]; // Return first column of primary key
                        }
                    }
                }
            }
            
            // Method 2: Check table fields for AUTO_INCREMENT
            $fields = fields($table);
            if ($fields) {
                foreach ($fields as $name => $field) {
                    if ($field['auto_increment']) {
                        return $name;
                    }
                }
            }
            
            // Method 3: Common primary key names
            $common_pk_names = array('id', $table . '_id', 'pk_' . $table);
            foreach ($common_pk_names as $pk_name) {
                if (isset($fields[$pk_name])) {
                    return $pk_name;
                }
            }
            
        } catch (Exception $e) {
            error_log("AdminerDescSort findPrimaryKey Error: " . $e->getMessage());
        }
        
        return null;
    }
}