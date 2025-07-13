<?php

/**
 * Adminer Plugin: Default DESC Sort
 * 
 * Automatically sorts table data in DESC order on primary key column by default
 * Uses JavaScript approach for maximum compatibility
 * 
 * @author italic
 * @version 3.1.0
 * @link https://github.com/germain-italic/adminer-docker-custom
 */

class AdminerDescSort extends AdminerPlugin {
    
    /**
     * Plugin name for display in loaded plugins
     */
    function name() {
        return "Default DESC Sort";
    }
    
    /**
     * Plugin version
     */
    function version() {
        return "3.1.0";
    }
    
    /**
     * Add JavaScript to automatically sort by primary key DESC
     * This approach is the most compatible and doesn't interfere with Adminer's internals
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
            
            // Look for primary key patterns
            for (var i = 0; i < headers.length; i++) {
                var headerText = headers[i].textContent.toLowerCase().trim();
                
                // Check for common primary key names
                if (headerText === "id" || 
                    headerText.endsWith("_id") || 
                    headerText === "pk" ||
                    headerText.indexOf("primary") !== -1) {
                    
                    // Add order parameters and redirect
                    url.searchParams.set("order[0]", headers[i].textContent);
                    url.searchParams.set("desc[0]", "1");
                    
                    // Only redirect if URL actually changed
                    if (window.location.href !== url.toString()) {
                        window.location.href = url.toString();
                        return;
                    }
                    break;
                }
            }
            
            // Fallback: if no obvious primary key found, try the first column
            if (headers.length > 0) {
                var firstHeader = headers[0].textContent.trim();
                if (firstHeader) {
                    url.searchParams.set("order[0]", firstHeader);
                    url.searchParams.set("desc[0]", "1");
                    
                    if (window.location.href !== url.toString()) {
                        window.location.href = url.toString();
                    }
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