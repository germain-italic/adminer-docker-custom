<?php

/**
 * Adminer Plugin: Default DESC Sort
 * 
 * Plugin to force DESC sorting by default on primary key columns
 * Compatible with standard Adminer plugin architecture
 * 
 * @author italic
 * @version 2.2.0
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
        return "2.2.0";
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
        
        try {
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
        } catch (Exception $e) {
            // Silent fail, return null
        }
        
        return null;
    }
    
    /**
     * Modify the select query to add default DESC ordering
     * This is called when building the SELECT query
     */
    function selectQueryBuild($select, $where, $group, $order, $limit, $page) {
        // If no order is specified, add default DESC order on primary key
        if (empty($order)) {
            $table = $_GET["select"] ?? null;
            if ($table) {
                $primaryKey = $this->getPrimaryKeyColumn($table);
                if ($primaryKey) {
                    $order = array($primaryKey => true); // true = DESC
                }
            }
        }
        
        // Return the modified parameters
        return array($select, $where, $group, $order, $limit, $page);
    }
    
    /**
     * Alternative approach: modify the ORDER BY clause directly
     */
    function selectOrderPrint($order, $columns, $indexes) {
        // Get current table name
        $table = $_GET["select"] ?? null;
        
        if (!$table || !empty($order)) {
            return false; // Let Adminer handle if table unknown or order already set
        }
        
        // Find primary key
        $primaryKey = $this->getPrimaryKeyColumn($table);
        
        if ($primaryKey && isset($columns[$primaryKey])) {
            // Set default DESC order on primary key
            echo '<input type="hidden" name="order[0]" value="' . h($primaryKey) . '">';
            echo '<input type="hidden" name="desc[0]" value="1">';
            return true; // Indicate we handled the order
        }
        
        return false;
    }
    
    /**
     * Hook into the select process to modify default behavior
     */
    function selectVal($val, $link, $field, $original) {
        // This method is called for each cell value
        // We use it as a hook to detect when we're in a select context
        static $orderSet = false;
        
        if (!$orderSet && !isset($_GET['order']) && isset($_GET['select'])) {
            $table = $_GET['select'];
            $primaryKey = $this->getPrimaryKeyColumn($table);
            
            if ($primaryKey) {
                // Modify the global GET parameters to include our default order
                $_GET['order'] = array($primaryKey);
                $_GET['desc'] = array('1');
                $orderSet = true;
            }
        }
        
        return $val; // Return original value unchanged
    }
    
    /**
     * Alternative: Hook into table display
     */
    function tablesPrint($tables) {
        // Add JavaScript to automatically add DESC sort when no order is specified
        echo '<script>
        document.addEventListener("DOMContentLoaded", function() {
            // Check if we are on a select page with no order specified
            var url = new URL(window.location.href);
            var hasSelect = url.searchParams.has("select");
            var hasOrder = url.searchParams.has("order");
            
            if (hasSelect && !hasOrder) {
                // Add default DESC order on primary key
                var table = url.searchParams.get("select");
                if (table) {
                    url.searchParams.set("order[0]", "id"); // Default to id, will be improved
                    url.searchParams.set("desc[0]", "1");
                    
                    // Only redirect if we are not already redirected
                    if (window.location.href !== url.toString()) {
                        window.location.href = url.toString();
                    }
                }
            }
        });
        </script>';
        
        return false; // Let Adminer handle normal table display
    }
}

// Return plugin instance for Adminer
return new AdminerDescSort();