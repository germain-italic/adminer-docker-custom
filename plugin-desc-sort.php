<?php

/**
 * Adminer Plugin: Default DESC Sort
 * 
 * Automatically sorts table data in DESC order on primary key column by default
 * Compatible with Adminer plugin architecture
 * 
 * @author italic
 * @version 3.0.0
 * @link https://github.com/germain-italic/adminer-docker-custom
 */

class AdminerDescSort {
    
    /**
     * Plugin name for display
     */
    function name() {
        return "AdminerDescSort";
    }
    
    /**
     * Plugin version
     */
    function version() {
        return "3.0.0";
    }
    
    /**
     * Plugin description for loaded plugins section
     */
    function description() {
        return "Automatically sorts table data in DESC order on primary key column by default";
    }
    
    /**
     * Get the primary key column name for a table
     */
    function getPrimaryKeyColumn($table) {
        global $connection;
        
        if (!$connection || !$table) {
            return null;
        }
        
        try {
            // Get table indexes to find primary key
            $indexes = indexes($table);
            
            if ($indexes) {
                foreach ($indexes as $index) {
                    if ($index["type"] == "PRIMARY") {
                        // Return the first column of the primary key
                        $columns = array_keys($index["columns"]);
                        if (!empty($columns)) {
                            return $columns[0];
                        }
                    }
                }
            }
            
            // Fallback: check for common primary key names
            $fields = fields($table);
            if ($fields) {
                $commonPkNames = array('id', 'ID', $table . '_id', $table . 'Id');
                
                foreach ($commonPkNames as $pkName) {
                    if (isset($fields[$pkName])) {
                        return $pkName;
                    }
                }
            }
        } catch (Exception $e) {
            // Silent fail, return null
        }
        
        return null;
    }
    
    /**
     * Modify the select query to add default DESC ordering
     * This is the main method that handles the default sorting
     */
    function selectQueryBuild($select, $where, $group, $order, $limit, $page) {
        // Only modify if no order is specified and we're selecting from a table
        if (empty($order) && isset($_GET["select"])) {
            $table = $_GET["select"];
            $primaryKey = $this->getPrimaryKeyColumn($table);
            
            if ($primaryKey) {
                // Add DESC order on primary key
                $order = array($primaryKey => true); // true = DESC in Adminer
            }
        }
        
        // Always return all parameters (required by Adminer)
        return array($select, $where, $group, $order, $limit, $page);
    }
    
    /**
     * Add JavaScript fallback for cases where selectQueryBuild doesn't work
     */
    function head() {
        // Only add JavaScript on select pages without existing order
        if (isset($_GET["select"]) && !isset($_GET["order"])) {
            echo '<script>
document.addEventListener("DOMContentLoaded", function() {
    // Check if we are on a select page with no order specified
    var url = new URL(window.location.href);
    var hasSelect = url.searchParams.has("select");
    var hasOrder = url.searchParams.has("order");
    
    if (hasSelect && !hasOrder) {
        // Find the primary key column in the table header
        var table = document.querySelector("table");
        if (table) {
            var headers = table.querySelectorAll("thead th a");
            var primaryKeyFound = false;
            
            // Look for common primary key patterns
            for (var i = 0; i < headers.length; i++) {
                var headerText = headers[i].textContent.toLowerCase();
                if (headerText === "id" || headerText.endsWith("_id")) {
                    // Add order parameters and redirect
                    url.searchParams.set("order[0]", headers[i].textContent);
                    url.searchParams.set("desc[0]", "1");
                    
                    if (window.location.href !== url.toString()) {
                        window.location.href = url.toString();
                    }
                    primaryKeyFound = true;
                    break;
                }
            }
        }
    }
});
</script>';
        }
    }
}

// Return plugin instance for Adminer
return new AdminerDescSort();